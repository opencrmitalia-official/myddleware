#!/usr/bin/env bash

## Load variables
source /run/crond.env

## Prepare log file
LOG_DIR=/var/www/html/var/logs
LOG_FILE=${LOG_DIR}/monitoring.log
LOG_TIMESTAMP=$(date +"%Y-%m-%d %H:%M")
touch ${LOG_FILE}
chmod 777 ${LOG_FILE}

## Clean-up log file
[ "$(wc -l < ${LOG_FILE})" -gt "1000" ] && sed -e '1,20d' -i ${LOG_FILE} || true

##
echo "==> ${LOG_TIMESTAMP} [INFO] Start monitoring..." >> ${LOG_FILE}
php /var/www/html/bin/console myddleware:monitoring --env=background >> ${LOG_FILE} 2>&1
echo "--" >> ${LOG_FILE}
