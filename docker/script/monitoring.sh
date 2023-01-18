#!/usr/bin/env bash

#echo "Ping: $MONITORING_URL"

php -f /var/www/html/bin/console myddleware:monitoring --env=background
