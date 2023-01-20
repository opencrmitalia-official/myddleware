#!/usr/bin/env bash

## Load variables
source /run/crond.env
export MONITORING_KEY MONITORING_URL
export MYDDLEWARE_CRON_RUN=1

## Run Monitoring Job
printenv >> /var/www/html/var/log/monitoring.log
php -f /var/www/html/bin/console myddleware:monitoring --env=background
