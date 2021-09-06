#!/usr/bin/env bash

## Load variables
source /run/crond.env

LOG_DIR=/var/www/html/var/logs/
LOG_FILE=${LOG_DIR}/monitoring.log


php /var/www/html/bin/console myddleware:monitoring --env=background
# >> ${LOG_FILE}
