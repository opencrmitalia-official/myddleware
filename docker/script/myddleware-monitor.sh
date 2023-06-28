#!/usr/bin/env bash

## Load variables
source /run/crond.env
export MYDDLEWARE_CRON_RUN=1

## Prepare log file
LOG_DIR=/var/www/html/var/log
LOG_FILE=${LOG_DIR}/monitoring.log
touch ${LOG_FILE}
chmod 777 ${LOG_FILE}

## Clean-up log file
[ "$(wc -l < ${LOG_FILE})" -gt "5000" ] && sed -e '1,20d' -i ${LOG_FILE} || true

## Run Monitoring Job
php -f /var/www/html/bin/console myddleware:monitoring --env=background
