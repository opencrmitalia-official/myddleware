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

namespace App\Command;

use App\Entity\Job;
use App\Manager\JobManager;
use App\Manager\NotificationManager;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class NotificationCommand.
 */
class MonitoringCommand extends Command
{
    private NotificationManager $notificationManager;
    private JobManager $jobManager;

    public function __construct(
        NotificationManager $notificationManager,
        JobManager $jobManager,
        $name = null)
    {
        parent::__construct($name);
        $this->notificationManager = $notificationManager;
        $this->jobManager = $jobManager;
    }

    protected function configure()
    {
        $this
            ->setName('myddleware:monitoring')
            ->setDescription('Run monitoring task')
            //->addArgument('type', InputArgument::OPTIONAL, 'Notification type')
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $monitoringUrl = getenv('MONITORING_URL');
        /*
        // We don't create job for alert
        if ('alert' == $input->getArgument('type')) {
            try {
                $this->notificationManager->sendAlert();
            } catch (\Exception $e) {
                $output->writeln('<error>'.$e->getMessage().'</error>');
            }
        }
        // Standard notification
        else {
            try {
                $data = $this->jobManager->initJob('notification');

                if (false === $data['success']) {
                    $output->writeln('0;<error>'.$data['message'].'</error>');

                    return 0;
                }

                $this->notificationManager->sendNotification();
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $output->writeln('<error>'.$message.'</error>');
            }
            // Close job if it has been created
            $this->jobManager->closeJob();
        }
        */

        $output->writeln('Ping: '.$monitoringUrl);


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://www.example.com/tester.phtml");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "postvar1=value1&postvar2=value2&postvar3=value3");

// In real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS,
//          http_build_query(array('postvar1' => 'value1')));

// Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

// Further processing ...
        if ($server_output == "OK") {

        } else {

        }


        return 1;
    }
}
