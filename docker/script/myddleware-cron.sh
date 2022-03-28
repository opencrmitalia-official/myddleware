#!/bin/bash

## Load variables
source /run/crond.env
export MYDDLEWARE_CRON_RUN=1

## Prepare log file
LOG_DIR=/var/www/html/var/log/scheduler/$(date +"%Y/%m/%d")
LOG_FILE=${LOG_DIR}/$(date +"%H%M").log
mkdir -p ${LOG_DIR}
touch ${LOG_FILE}
chmod 777 -R ${LOG_DIR}

## Execute jobs
echo $(date): Start Myddleware Sync >> ${LOG_FILE}
php /var/www/html/bin/console cron:run >> ${LOG_FILE} 2>&1
echo $(date): End Myddleware Sync >> ${LOG_FILE}
echo "--" >> ${LOG_FILE}
