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
use Symfony\Component\Console\Output\OutputInterface;

class TaskCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
            ->setName('myddleware:synchro')
            ->setDescription('Synchronisation des données')
            ->addArgument('rule', InputArgument::REQUIRED, "Alias de la règle")
			->addArgument('api', InputArgument::OPTIONAL, "Call from API")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$step = 1;
		try {		
			$logger = $this->getContainer()->get('logger');		
					
			// Source -------------------------------------------------
			// alias de la règle en params
			$rule = $input->getArgument('rule');
			$api = $input->getArgument('api');

			// Récupération du Job			
			$job = $this->getContainer()->get('myddleware_job.job');
			// Clear message in case this task is run by jobscheduler. In this case message has to be refreshed.
			$job->message = '';		
			$job->setApi($api);					
			
			if ($job->initJob('Synchro : '.$rule)) {
                if (getenv('MYDDLEWARE_CRON_RUN')) {
                    echo "Synchro: $rule\n";
                }
                $output->writeln( '1;'.$job->id );  // Not removed, user for manual job and webservices
                if (!empty($rule)) {
					if ($rule == 'ERROR') {
						// Premier paramètre : limite d'enregistrement traités
						// Deuxième paramètre, limite d'erreur : si un flux a plus de tentative que le paramètre il n'est pas relancé
						$job->runError( 50 , 100);	
					}
					else {
						// Envoi du job sur toutes les règles demandées. Si ALL est sélectionné alors on récupère toutes les règle dans leur ordre de lancement sinon on lance seulement la règle demandée.
						if ($rule == 'ALL') {
							$rules = $job->getRules();
						}
						else {
							$rules[] = $rule;
						}								
						if (!empty($rules)) {
							foreach ($rules as $key => $value) {
								// Don't display rule id if the command is called from the api
								if (empty($api)) {
									echo "Rule: {$value}\n";
								}
                                if (getenv('MYDDLEWARE_CRON_RUN')) {
                                    echo "====[ Processing: $value ]====\n";
                                }
								$output->writeln('Read data for rule : <question>'.$value.'</question>');
								// Chargement des données de la règle
								if ($job->setRule($value)) {		
									// Sauvegarde des données sources dans les tables de myddleware
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Create documents...'."\n";
                                    }
                                    $output->writeln($value.' : Create documents.');
									$nb = $job->createDocuments();
									$output->writeln($value.' : Number of documents created : '.$nb);
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Number of documents created: '.$nb."\n";
                                    }

									// Permet de filtrer les documents
									$job->filterDocuments();
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Filter OK!'."\n";
                                    }

									// Permet de valider qu'aucun document précédent pour la même règle et le même id n'est pas bloqué
									$job->ckeckPredecessorDocuments();
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Predecessor OK!'."\n";
                                    }

                                    // Permet de valider qu'au moins un document parent(relation père) est existant
									$job->ckeckParentDocuments();
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Parent OK!'."\n";
                                    }

									// Permet de transformer les docuement avant d'être envoyés à la cible
									$job->transformDocuments();
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Transform OK!'."\n";
                                    }

									// Historisation des données avant modification dans la cible
									$job->getTargetDataDocuments();
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Target data OK!'."\n";
                                    }

									// Envoi des documents à la cible
									$job->sendDocuments();
                                    if (getenv('MYDDLEWARE_CRON_RUN')) {
                                        echo $value.' => Send OK!'."\n";
                                    }
                                }
							}
						}
					}
				}	
			}
		}
		catch(\Exception $e) {
			$job->message .= $e->getMessage();
		}
		
		// Close job if it has been created
		if($job->createdJob === true) {
            if (getenv('MYDDLEWARE_CRON_RUN')) {
                echo 'Closing the task...'."\n";
            }
			$job->closeJob();
		} else {
            if (getenv('MYDDLEWARE_CRON_RUN')) {
                echo 'Closing task problem.'."\n";
            }
        }
		
		// Retour en console --------------------------------------
		if (!empty($job->message)) {
			$output->writeln( '0;<error>'.$job->message.'</error>');
			$logger->error( $job->message );
            if (getenv('MYDDLEWARE_CRON_RUN')) {
                echo "Error: $job->message\n";
            }
		}

        if (getenv('MYDDLEWARE_CRON_RUN')) {
            echo "[synchro exit]\n";
        }
    }
}