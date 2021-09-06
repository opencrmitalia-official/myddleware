#!/bin/bash

## Load variables
source /run/crond.env

## Prepare log file
LOG_DIR=/var/www/html/var/logs/scheduler/$(date +"%Y/%m/%d")
LOG_FILE=${LOG_DIR}/$(date +"%H%M").log
mkdir -p ${LOG_DIR}
touch ${LOG_FILE}
chmod 777 -R ${LOG_DIR}

## Execute jobs
echo $(date): Start Myddleware Sync >> /var/log/cron.log
echo $(date): Start Myddleware Sync >> ${LOG_FILE}

php /var/www/html/bin/console myddleware:jobScheduler --env=background >> ${LOG_FILE}

echo $(date): End Myddleware Sync >> /var/log/cron.log
echo $(date): End Myddleware Sync >> ${LOG_FILE}

## Custom Scheduler
if [[ -f /var/www/html/scheduler.sh ]]; then
  chmod +x /var/www/html/scheduler.sh
  bash /var/www/html/scheduler.sh >> /var/www/html/var/logs/scheduler.log 2>&1
  chmod 777 /var/www/html/var/logs/scheduler.log
fi
