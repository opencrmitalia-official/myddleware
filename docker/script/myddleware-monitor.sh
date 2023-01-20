#!/usr/bin/env bash

## Load variables
cat /run/crond.env
export MYDDLEWARE_CRON_RUN=1

## Run Monitoring Job
php -f /var/www/html/bin/console myddleware:monitoring --env=background
