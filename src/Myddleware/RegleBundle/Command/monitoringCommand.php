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

        $this->sendLastUpdatesToCrm($customJson);

        $this->problemNotifications();

        file_get_contents($notificationFile, json_encode($notificationStatus, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
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
                    $waitForNotification = round($alertReminderTime - ((time() - @$notificationStatus['last_alert']) / 60));
                    if ($waitForNotification < 0) {
                        echo "Sending alert notification for '{$instanceName}'...\n";
                        $notificationStatus['last_alert'] = time();
                        $notification->sendAlert($alertTimeLimit);
                        echo "Messages OK!\n";
                        break;
                    } else {
                        echo "Postpone alert notification for '{$instanceName}' due to reminder until $waitForNotification minutes.\n";
                    }
                }
            }
        }
    }
}
