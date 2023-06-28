<?php
/*********************************************************************************
 * This file is part of Myddleware.

 * @package Myddleware
 * @copyright Copyright (C) 2013 - 2015  Stéphane Faure - CRMconsult EURL
 * @copyright Copyright (C) 2015 - 2016  Stéphane Faure - Myddleware ltd - contact@myddleware.com
 * @link http://www.myddleware.com	
 
 This file is part of Myddleware.
 
 Myddleware is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Myddleware is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Myddleware.  If not, see <http://www.gnu.org/licenses/>.
*********************************************************************************/

namespace Myddleware\RegleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;

class monitoringCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
            ->setName('myddleware:monitoring')
            ->setDescription('Monitoring routine')
			//->addArgument('type',InputArgument::OPTIONAL, "Notification type")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $customJson = [];
        $alertTimeLimit = 0;
        $alertReminderTime = 0;
        $instanceName = 'Myddleware';
        $notificationFile = $this->getContainer()->get('kernel')->getLogDir().'/notification.json';
        $notificationStatus = [];
        if (file_exists($notificationFile)) {
            $notificationStatus = json_decode(file_get_contents($notificationFile), true);
        }

        if (file_exists($customJsonFile = __DIR__.'/../Custom/Custom.json')) {
            $customJson = json_decode(file_get_contents($customJsonFile), true);
            $instanceName = isset($customJson['instance_name']) && $customJson['instance_name'] ? $customJson['instance_name'] : 'Myddleware';
            $alertTimeLimit = isset($customJson['alert_time_limit']) ? intval($customJson['alert_time_limit']) : 0;
            $alertReminderTime = isset($customJson['alert_reminder_time']) ? intval($customJson['alert_reminder_time']) : 0;
        }

        $this->alertNotification(
            $instanceName,
            $notificationStatus,
            $alertTimeLimit,
            $alertReminderTime
        );

        $this->sendLastUpdatesToCrmFeedback($instanceName, $notificationStatus, $customJson);

        $this->problemNotification($instanceName, $notificationStatus);

        file_put_contents($notificationFile, json_encode($notificationStatus, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->monitoringCurlPolling($input, $output);
	}

    /**
     *
     */
    protected function alertNotification($instanceName, &$notificationStatus, $alertTimeLimit, $alertReminderTime)
    {
        $db = $this->getContainer()->get('database_connection');
        $notification = $this->getContainer()->get('myddleware.notification');

        $sql = "SELECT *, TIMESTAMPDIFF(MINUTE, begin, NOW()) AS busy_time FROM Job WHERE status = 'Start'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $jobs = $stmt->fetchAll();

        if (count($jobs) > 0) {
            foreach ($jobs as $job) {
                if ($alertTimeLimit > 0 && $job['busy_time'] > $alertTimeLimit) {
                    $waitForNotification = floor($alertReminderTime - ((time() - @$notificationStatus['last_alert']) / 60)) + 1;
                    if ($waitForNotification <= 0) {
                        echo "Sending alert notification for '{$instanceName}'...\n";
                        $notificationStatus['last_alert'] = time();
                        $notification->sendAlert($alertTimeLimit);
                        echo "Messages OK!\n";
                        break;
                    } else {
                        echo "Postpone alert notification due to reminder in less than $waitForNotification minutes.\n";
                    }
                }
            }
        }
    }

    /**
     *
     */
    protected function sendLastUpdatesToCrmFeedback($instanceName, &$notificationStatus, $customJson)
    {
        if (empty($customJson['feedback_crm_url']) || empty($customJson['feedback_crm_username'])) {
            echo "Empty CRM feedback settings.\n";
            return;
        }

        $waitForFeedback = floor($customJson['feedback_time'] - ((time() - @$notificationStatus['last_feedback']) / 60)) + 1;
        if ($waitForFeedback > 0) {
            echo "Postpone feedback due to time settings in less then $waitForFeedback minutes.\n";
            return;
        }

        $notificationStatus['last_feedback'] = time();

        $db = $this->getContainer()->get('database_connection');
        $sql = "SELECT `end` FROM Job WHERE `status` = 'End' ORDER BY `end` DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $job = $stmt->fetch();
        if (empty($job['end']) || !preg_match('/202[0-9]-[01][0-9]-[0-3][0-9] [012][0-9]:[0-5][0-9]:[0-5][0-9]/', $job['end'])) {
            echo "Corrupted value on last job '{$job['end']}'.\n";
            return;
        }
        $jobDate = date('Y-m-d', strtotime($job['end']));
        $jobTime = date('H:i:s', strtotime($job['end']));

        $client = new \Javanile\VtigerClient\VtigerClient([
            'endpoint' => $customJson['feedback_crm_url'],
        ]);

        $response = $client->login($customJson['feedback_crm_username'], $customJson['feedback_crm_access_key']);
        if (empty($response['success'])) {
            echo "Access problem with CRM for feedback.\n";
            return;
        }

        $response = $client->revise($customJson['feedback_crm_module'], [
            'id' => $customJson['feedback_crm_record'],
            $customJson['feedback_crm_date_field'] => $jobDate,
            $customJson['feedback_crm_time_field'] => $jobTime,
        ]);
        if (empty($response['success'])) {
            echo "Communication problem with CRM for feedback.\n";
            return;
        }

        echo "Updated CRM feedback for instance '{$instanceName}' with '{$response['result'][$customJson['feedback_crm_date_field']]}' and '{$response['result'][$customJson['feedback_crm_time_field']]}'.\n";
    }

    /**
     *
     */
    protected function problemNotification($instanceName, &$notificationStatus)
    {
        $notification = $this->getContainer()->get('myddleware.notification');
        if (!$notification->hasProblem()) {
            return;
        }

        $notification->sendNotification(true);
    }

    /**
     *
     */
    protected function monitoringCurlPolling(InputInterface $input, OutputInterface $output)
    {
        $env = parse_ini_file('/var/www/html/.env', false, INI_SCANNER_RAW);

        $monitoringKey = @$env['monitoring_key'];
        if (empty($monitoringKey)) {
            $monitoringKey = 'myddleware';
        }

        $monitoringUrl = @$env['monitoring_url'];
        #file_put_contents('/var/www/html/var/logs/monitoring.log', "KEY: ".$monitoringUrl."\n", FILE_APPEND);
        if (empty($monitoringUrl)) {
            return 0;
        }

        $sqlRule = "SELECT * FROM Job WHERE Status='Start' ORDER BY begin ASC LIMIT 1";
        $db = $this->getContainer()->get('database_connection');
        $stmt = $db->prepare($sqlRule);
        $stmt->execute();
        $job = $stmt->fetchAssociative();

        #$errors = $this->ruleRepository->errorByRule();
        #var_dump($errors);

        $output->writeln('Ping: '.$monitoringUrl);

        $payload = [
            'ts' => date('Y-m-d H:i:s'),
            'key' => $monitoringKey,
            'current_open_job_begin' => $job['begin'],
            'current_open_job_param' => $job['param'],
        ];

        $output->writeln('Data: '.json_encode($payload));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $monitoringUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        file_put_contents('/var/www/html/var/logs/monitoring.log', json_encode([
                'payload' => $payload,
                'response' => $response,
                'ping' => $monitoringUrl,
            ], JSON_UNESCAPED_SLASHES)."\n", FILE_APPEND);

        $output->writeln('Info: '.$response);

        return 0;
    }
}
