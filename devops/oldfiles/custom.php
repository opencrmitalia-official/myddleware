<?php

namespace Myddleware\RegleBundle\Solutions;

class microsoftsqlbase extends microsoftsqlcore
{


    public function read($param)
    {
        // Redefine reference date format (add milliseconds)
        $date = new \DateTime($param['date_ref']);
        $param['date_ref'] = $date->format('Y-m-d H:i:s.v');

        // Call standard read function
        $result = parent::read($param);

        // Return to caller
        return $result;
    }

    // Redifine read function
    protected function queryValidation($param, $func, $sql)
    {
        $log = date('Y-m-d H:i:s').' ['.$func.' '.$this->getCurrentRuleId($param).'] params: '.json_encode($param).' - query: '.$sql;
        file_put_contents(__DIR__.'/../../../../../var/logs/microsoftsql.log', $log."\n", FILE_APPEND);
        return $sql;
    }

    // Cattura l'ID di una regola durante la simulazione
    public function getCurrentRuleId($param = null)
    {
        if (isset($param['rule']['id'])) {
            return $param['rule']['id'];
        }

        if (preg_match('@^/app\.php/rule/simule/([a-z0-9]+)@', $_SERVER['REQUEST_URI'], $matches)) {
            return $matches[1];
        }

        return false;
    }
}

$file = __DIR__.'/microsoftsql.client.php';
if (file_exists($file)) {
    require_once $file;
} else {
    class microsoftsql extends microsoftsqlbase {}
}
