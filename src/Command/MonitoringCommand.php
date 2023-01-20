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
use App\Repository\RuleRepository;
use Doctrine\DBAL\Connection as DriverConnection;
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
    protected DriverConnection $dbalConnection;
    private RuleRepository $ruleRepository;

    public function __construct(
        NotificationManager $notificationManager,
        JobManager $jobManager,
        DriverConnection $dbalConnection,
        RuleRepository $ruleRepository,
        $name = null)
    {
        parent::__construct($name);
        $this->notificationManager = $notificationManager;
        $this->jobManager = $jobManager;
        $this->dbalConnection = $dbalConnection;
        $this->ruleRepository = $ruleRepository;
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
        $monitoringKey = getenv('MONITORING_KEY');
        if (empty($monitoringKey)) {
            $monitoringKey = 'myddleware';
        }

        $monitoringUrl = getenv('MONITORING_URL');
        if (empty($monitoringUrl)) {
            return 0;
        }

        $sqlRule = "SELECT * FROM job WHERE Status='Start' ORDER BY begin ASC LIMIT 1";
        $stmt = $this->dbalConnection->prepare($sqlRule);
        $result = $stmt->executeQuery();
        $job = $result->fetchAssociative();

        #$errors = $this->ruleRepository->errorByRule();
        #var_dump($errors);

        #$output->writeln('Ping: '.$monitoringUrl);
        echo 'Ping: '.$monitoringUrl."\n";

        $payload = [
            'ts' => date('Y-m-d H:i:s'),
            'key' => $monitoringKey,
            'current_open_job_begin' => $job['begin'],
            'current_open_job_param' => $job['param'],
        ];

        #$output->writeln('Data: '.json_encode($payload));
        echo 'Data: '.json_encode($payload)."\n";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $monitoringUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        #$output->writeln('Info: '.$response);
        echo 'Info: '.$response;

        return 0;
    }
}
