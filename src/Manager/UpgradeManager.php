<?php
/*********************************************************************************
 * This file is part of Myddleware.

 * @package Myddleware
 * @copyright Copyright (C) 2013 - 2015  Stéphane Faure - CRMconsult EURL
 * @copyright Copyright (C) 2015 - 2017  Stéphane Faure - Myddleware ltd - contact@myddleware.com
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

namespace App\Manager;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Ldap\Adapter\ConnectionInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\PhpExecutableFinder;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Config;

$file = __DIR__.'/../Custom/Manager/UpgradeManager.php';
if (file_exists($file)) {
    require_once $file;
} else {
    /**
     * Class UpgradeManager.
     *
     * @package App\Manager
     *
     *
     */
    class UpgradeManager
    {
        protected $env;
        protected $em;
        protected $phpExecutable = 'php';
        protected $message = '';
        protected $defaultEnvironment = ['prod' => 'prod', 'background' => 'background'];
        /**
         * @var LoggerInterface
         */
        private $logger;
        /**
         * @var string
         */
        private $projectDir;
        /**
         * @var KernelInterface
         */
        private $kernel;
		/**
		 * @var EntityManagerInterface
		 */
		private $entityManager;
		
        public function __construct(
            LoggerInterface $logger,
            KernelInterface $kernel,
			EntityManagerInterface $entityManager
        ) {
            $this->logger = $logger; 
            $this->kernel = $kernel;
			$this->entityManager = $entityManager;
            $this->env = $kernel->getEnvironment();
            $this->projectDir = $kernel->getProjectDir();
			// Initialise parameters
			$configRepository = $this->entityManager->getRepository(Config::class);
			$configs = $configRepository->findAll();
			if (!empty($configs)) {
				foreach ($configs as $config) {
					$this->params[$config->getName()] = $config->getvalue();
				}
			}

			// Get the php executable 
			$phpBinaryFinder = new PhpExecutableFinder();
			$phpBinaryPath = $phpBinaryFinder->find();
			$this->phpExecutable = $phpBinaryPath;
        }

        public function processUpgrade($output)
        {
            try {
                // Customize update process
                $this->beforeUpdate($output);

                // Update file
                $output->writeln('<comment>Update files...</comment>');
                $this->updateFiles();
                $output->writeln('<comment>Update files OK</comment>');
                $this->message .= 'Update files OK'.chr(10);

                // Update vendor via composer
                $output->writeln('<comment>Update vendors...</comment>');
                $this->updateVendors();
                $output->writeln('<comment>Update vendors OK</comment>');
                $this->message .= 'Update vendors OK'.chr(10);

                // Update database
                $output->writeln('<comment>Update database...</comment>');
                $this->updateDatabase();
                $output->writeln('<comment>Update database OK</comment>');
                $this->message .= 'Update database OK'.chr(10);

                // Change Myddleware version
                $output->writeln('<comment>Finish install...</comment>');
                $this->finishInstall();
                $output->writeln('<comment>Finish install OK</comment>');
                $this->message .= 'Finish install OK'.chr(10);

                // Clear cache
                $output->writeln('<comment>Clear Symfony cache...</comment>');
                $this->clearSymfonycache();
                $output->writeln('<comment>Clear Symfony cache OK</comment>');
                $this->message .= 'Clear Symfony cache OK'.chr(10);

                // Customize update process
                $this->afterUpdate($output);

                $output->writeln('<info>Myddleware has been successfully updated in version '.$this->params['myd_version'].'</info>');
                $this->message .= 'Myddleware has been successfully updated in version '.$this->params['myd_version'].chr(10);
            } catch (\Exception $e) {
                $error = 'Error : '.$e->getMessage().' '.$e->getFile().' Line : ( '.$e->getLine().' )';
                $this->logger->error($error);
                $this->message .= $error.chr(10);
                $output->writeln('<error>'.$error.'</error>');
            }

            return $this->message;
        }

        protected function updateFiles()
        {
            // Update master if git_branch is empty otherwise we update the specific branch
            $command = (!empty($this->params['git_branch'])) ? 'git pull origin '.$this->params['git_branch'] : 'git pull';
            $process = new Process($command);
            $process->run();
            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $output1 = $process->getOutput();
            if (false !== strpos($output1, 'Aborting')) {
                echo $process->getOutput();
                $this->logger->error($process->getOutput());
                $this->message .= $process->getOutput().chr(10);
                throw new \Exception('Failed to update Myddleware. Failed to update Myddleware files by using git');
            }

            // Run the command a second time, we expect to get the message "Already up-to-date"
            $process = new Process($command);
            $process->run();
            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $output2 = $process->getOutput();
            echo $output2;
            $this->message .= $output2.chr(10);
            if (
                false === strpos($output2, 'Already up to date')
                and false === strpos($output2, 'Already up-to-date')
            ) {
                throw new \Exception('Failed to update Myddleware. Files are not up-to-date.');
            }
        }

        // Update vendors via composer
        protected function updateVendors()
        {
            $process = new Process($this->phpExecutable.' composer.phar install --no-plugins');
            $process->run();
            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }

        // Clear boostrap cache
        protected function clearBoostrapCache()
        {
            $process = new Process($this->phpExecutable.' vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php');
            $process->run();
            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }

        // Update database
        protected function updateDatabase()
        {
            // Update schema
            $application = new Application($this->kernel);
            $application->setAutoExit(false);
            $arguments = [
                'command' => 'doctrine:schema:update',
                '--force' => true,
                '--env' => $this->env,
            ];

            $input = new ArrayInput($arguments);
            $output = new BufferedOutput();
            $application->run($input, $output);

            $content = $output->fetch();
            if (!empty($content)) {
                echo $content.chr(10);
                $this->logger->info($content);
                $this->message .= $content.chr(10);
            }

            // Update data
            $argumentsFixtures = [
                'command' => 'doctrine:fixtures:load',
                '--append' => true,
                '--env' => $this->env,
            ];

            $input = new ArrayInput($argumentsFixtures);
            $output = new BufferedOutput();
            $application->run($input, $output);

            $content = $output->fetch();
            // Send output to the logfile if debug mode selected
            if (!empty($content)) {
                echo $content.chr(10);
                $this->logger->info($content);
                $this->message .= $content.chr(10);
            }
        }

        // Clear Symfony cache
        protected function clearSymfonycache()
        {
            // Add current environement  to the default list
            $this->defaultEnvironment[$this->env] = $this->env;

            foreach ($this->defaultEnvironment as $env) {
                // Command clear cach remove only current environment cache
                if ($this->env == $env) {
                    // Clear cache
                    $application = new Application($this->kernel);
                    $application->setAutoExit(false);
                    $arguments = [
                        'command' => 'cache:clear',
                        '--env' => $env,
                    ];

                    $input = new ArrayInput($arguments);
                    $output = new BufferedOutput();
                    $application->run($input, $output);

                    $content = $output->fetch();
                    // Send output to the logfile if debug mode selected
                    if (!empty($content)) {
                        echo $content.chr(10);
                        $this->logger->info($content);
                        $this->message .= $content.chr(10);
                    }
                } else {
                    // CLear other environment cache via command
                    $command = 'rm -rf var/cache/'.$env.'/*';
                    $process = new Process($command);
                    $process->run();
                    // executes after the command finishes
                    if (!$process->isSuccessful()) {
                        throw new ProcessFailedException($process);
                    }
                    echo $process->getOutput();
                    $this->logger->error($process->getOutput());
                    $this->message .= $process->getOutput().chr(10);
                }
            }
        }

        // Finish install
        protected function finishInstall()
        {
            // Update schema
            $application = new Application($this->kernel);
            $application->setAutoExit(false);
            $arguments = [
                'command' => 'assetic:dump',
                '--env' => $this->env,
            ];

            $input = new ArrayInput($arguments);
            $output = new BufferedOutput();
            $application->run($input, $output);

            $content = $output->fetch();
            // Send output to the logfile if debug mode selected
            if (!empty($content)) {
                echo $content.chr(10);
                $this->logger->info($content);
                $this->message .= $content.chr(10);
            }

            // Update data
            $argumentsFixtures = [
                'command' => 'assets:install',
                '--env' => $this->env,
            ];

            $input = new ArrayInput($argumentsFixtures);
            $output = new BufferedOutput();
            $application->run($input, $output);

            $content = $output->fetch();
            // Send output to the logfile if debug mode selected
            if (!empty($content)) {
                echo $content.chr(10);
                $this->logger->info($content);
                $this->message .= $content.chr(10);
            }
        }

        // Function to customize the update process
        protected function beforeUpdate($output)
        {
        }

        // Function to customize the update process
        protected function afterUpdate($output)
        {
        }
    }
}