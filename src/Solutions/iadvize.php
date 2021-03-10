<?php
/*********************************************************************************
 * This file is part of Myddleware.
 * @package Myddleware
 * @copyright Copyright (C) 2013 - 2015  Stéphane Faure - CRMconsult EURL
 * @copyright Copyright (C) 2015 - 2020  Stéphane Faure - Myddleware ltd - contact@myddleware.com
 * @link http://www.myddleware.com
 *
 * This file is part of Myddleware.
 *
 * Myddleware is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Myddleware is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Myddleware.  If not, see <http://www.gnu.org/licenses/>.
 *********************************************************************************/

namespace App\Solutions;

use Iadvize\ApiRestClient\Client;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class iadvizecore extends solution
{
    protected $client;
    protected $defaultLimit = 100;
    protected $callLimit = 100;
    protected $delaySearch = '-7 day';

    // Requiered fields for each modules
    protected $required_fields = [
        'default' => ['id', 'created_at'],
        'visitor' => ['unique_id', 'created_at'],
    ];
    // Specify the field id for each module
    protected $idByModule = [
        'visitor' => 'unique_id',
    ];

    // List of field required to connect to Iadvize
    public function getFieldsLogin()
    {
        return [
            [
                'name' => 'apikey',
                'type' => PasswordType::class,
                'label' => 'solution.fields.apikey',
            ],
        ];
    }

    // Connect to Iadvize
    public function login($paramConnexion)
    {
        parent::login($paramConnexion);
        try {
            // Create client
            $this->client = new Client();
            $this->client->setAuthenticationKey($this->paramConnexion['apikey']);

            // Get resource
            $websites = $this->client->getResources('website', true);
            if (!empty($websites[0]['id'])) {
                $this->connexion_valide = true;
            } else {
                $message = $this->client->getLastResponse()->getMeta()->getMessage();
                if (!empty($message)) {
                    throw new \Exception('Connexion to Advize failed : '.$message);
                }
                throw new \Exception('Connexion to Advize failed : Invalide API key');
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $this->logger->error($error);

            return ['error' => $error];
        }
    }

    // login($paramConnexion)*/

    // Return the list of available modules
    public function get_modules($type = 'source')
    {
        if ('source' == $type) {
            $modules = [
                'visitor' => 'Visitor',
                'conversation.json-unicode' => 'Conversation',
            ];
        }

        return $modules;
    }

    // get_modules()

    // Get the fields available for the module in input
    public function get_module_fields($module, $type = 'source', $param = null)
    {
        parent::get_module_fields($module, $type);
        try {
            $this->moduleFields = [];
			
            // Use Iadvize metadata
            require 'lib/iadvize/metadata.php';
            if (!empty($moduleFields[$module])) {
                $this->moduleFields = $moduleFields[$module];
            }
            return $this->moduleFields;
        } catch (\Exception $e) {
            return false;
        }
    }
    // get_module_fields($module)


    /**
     * Function read data.
     *
     * @param $param
     *
     * @return mixed
     */
    public function readData($param)
    {
        try {
            $result = [];
            $result['count'] = 0;
            $result['date_ref'] = $param['date_ref'];

            if (empty($param['ruleParams']['limit'])) {
                $param['ruleParams']['limit'] = $this->defaultLimit;
            }

            // Remove Myddleware 's system fields
            $param['fields'] = $this->cleanMyddlewareElementId($param['fields']);

            // Add required fields
            $param['fields'] = $this->addRequiredField($param['fields'], $param['module']);

            // In case we search a specific record with an ID, we call the function getResource
            if (!empty($param['query']['id'])) {
                // Specific call for visitors because we take the reference field unique_id (not id)
                if ('visitor' == $param['module']) {
                    $visitors = $this->client->getResources($param['module'], true, ['unique_id' => $param['query']['id']], $param['fields'], 1, 1);
                    $record = current($visitors);
                } else {
                    $record = $this->client->getResource($param['module'], $param['query']['id']);
                }
                if (!empty($record)) {
                    $fieldId = (!empty($this->idByModule[$param['module']]) ? $this->idByModule[$param['module']] : 'id');
                    $record['id'] = $record[$fieldId];
                    $record['date_modified'] = $record['created_at'];
                    $records[$record[$fieldId]] = $record;
                }
                // Search by other fields (duplicate fields)
            } elseif (!empty($param['query'])) { // Iadvise used only in source so we don't have to develop this part
                // $records = $this->client->getResources($param['module'],true,$param['query'],$param['fields'],1,10);
            // Search By reference
            } else {
                $records = $this->readRecords($param);
            }

            if (!empty($records)) {
                $result['values'] = $records;
                $result['count'] = count($records);
                $result['date_ref'] = current($records)['created_at'];
            }
        } catch (\Exception $e) {
            $result['error'] = 'Error : '.$e->getMessage().' '.__CLASS__.' Line : ( '.$e->getLine().' )';
        }

        return $result;
    }

    // end function read

    protected function readRecords($param)
    {
        switch ($param['module']) {
            case 'visitor':
                return $this->readVisitors($param);
                break;
            case 'conversation.json-unicode':
                return $this->readConversation($param);
                break;
            default:
                throw new \Exception('Module '.$param['module'].' unknown. ');
        }
    }

    // Read visitors
    // We cant' read visitor using date, so we have to read it until we reach the reference date
    protected function readVisitors($param)
    {
        $page = 1;
        $stop = false;
        do {
            $result = [];
            // Call Iadvize
            $result = $this->client->getResources($param['module'], true, [], $param['fields'], $page, $this->callLimit);
            // If empty, whe check the last record of the result
            if (!empty($result)) {
                foreach ($result as $record) {
                    // if the last records is greater than the reference date, we week all records
                    if ($record['created_at'] > $param['date_ref']) {
                        $record['date_modified'] = $record['created_at'];
                        $record['id'] = $record['unique_id'];
                        $records[$record['unique_id']] = $record;
                    } else {
                        $stop = true;
                        break;
                    }
                }
            } else {
                $stop = true;
            }
            ++$page;
        } while (!$stop);

        if (empty($records)) {
            return;
        }
        // We have to returns only the number of records corresponding to the limit
        // We start by the end of the array (record sorted by created_at ASC)
        $offset = $param['ruleParams']['limit'] * (-1);

        return array_slice($records, $offset, null, true);
    }

    // Read conversation
    // It is possible to read conversation from a date but the reading interval can't exceed 3 months
    protected function readConversation($param)
    {
        $page = 1;
        $stop = false;
        $records = [];

        // Add 1 second to date_ref to avoid to read again the last records (from vilter is a >= not a > )
        $dateRefObj = new \DateTime($param['date_ref']);
        $dateRefModified = date_modify($dateRefObj, '+1 seconde');
        $dateRef = $dateRefModified->format('Y-m-d H:i:s');
        do {
            $result = [];
            // Call Iadvize conversation from date_ref
            $result = $this->client->getResources($param['module'], true, ['from' => $dateRef], $param['fields'], $page, $this->callLimit);
            // If empty, whe check the last record of the result
            if (!empty($result)) {
                foreach ($result as $record) {
                    // if the record has already been read, we stop reading
                    if (empty($records[$record['id']])) {
                        $record['date_modified'] = $record['created_at'];
                        $records[$record['id']] = $record;
                    } else {
                        $stop = true;
                        break;
                    }
                }
            } else {
                $stop = true;
            }
            ++$page;
            // A problem with this function is when we reach the end of the record list and call the next page, the same result is returned infinitely
        // To avoid the infinite loop we test
            // - if the limit call is greater than the result number : it means that we have read all records
            // - if a record has alredy been read, we stop to read as well. It happens when the last page contains exactly the same number of record than the limit call
        } while (
                !$stop
            and count($result) == $this->callLimit
        );
        if (empty($records)) {
            return;
        }
        // We have to returns only the number of records corresponding to the limit
        // We start by the end of the array (record sorted by created_at ASC)
        $offset = $param['ruleParams']['limit'] * (-1);

        return array_slice($records, $offset, null, true);
    }
}

/* * * * * * * *  * * * * * *  * * * * * *
    Include custom file if exists : used to redefine Myddleware standard code
 * * * * * *  * * * * * *  * * * * * * * */
$file = __DIR__.'/../Custom/Solutions/iadvize.php';
if (file_exists($file)) {
    require_once $file;
} else {
    // Otherwise, we use the current class (in this file)
    class iadvize extends iadvizecore
    {
    }
}