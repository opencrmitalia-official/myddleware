#!make

init:
	@docker-compose -f docker/env/script.yml run --rm --no-deps myddleware bash docker/script/init.sh

clean:
	@docker-compose -f docker/env/script.yml run --rm --no-deps myddleware bash docker/script/clean.sh

wait:
	@docker-compose run --rm myddleware bash -c "\
		echo -n 'Waiting the database bootstrapping ...' && \
		while ! (mysqladmin ping -umyddleware -hmysql > /dev/null 2>&1); do sleep 1; echo -n '.'; done && \
		echo ' done'"

clean-cache:
	@docker-compose -f docker-compose.yml run --rm myddleware rm -fr var/cache/*

update: init up
	@docker-compose -f docker-compose.yml run --rm myddleware rm -fr var/cache/* vendor
	@docker-compose -f docker-compose.yml run --rm myddleware chmod 777 -R var/cache/
	@docker-compose -f docker-compose.yml run --rm myddleware php composer2.phar install --ignore-platform-reqs --no-scripts
	@echo "Update done."

refresh: init up
	@docker-compose -f docker-compose.yml run --rm myddleware rm -fr var/cache/*
	@docker-compose -f docker-compose.yml run --rm myddleware chmod 777 -R var/cache/
	@docker-compose -f docker-compose.yml up -d --force-recreate myddleware

dump-autoload: init up
	@docker-compose -f docker-compose.yml run --rm myddleware php composer.phar dump-autoload --no-scripts

require-vtiger-client:
	@docker-compose -f docker-compose.yml run --rm myddleware php composer2.phar require javanile/vtiger-client:0.0.28 -vvvv --ignore-platform-reqs --no-scripts

require-woocommerce-client:
	@docker-compose -f docker-compose.yml run --rm myddleware php composer.phar require automattic/woocommerce:^3.0.0 -vvvv --ignore-platform-reqs --no-scripts

setup: up js-build setup-database
	@echo "Myddleware files and database setup completed."

setup-database: wait init
	@docker-compose run --rm myddleware bash docker/script/setup-database.sh

schedule:
	@docker-compose -f docker-compose.yml exec myddleware php -f /var/www/html/bin/console myddleware:resetScheduler --env=background
	@docker-compose -f docker-compose.yml exec -e MYDDLEWARE_CRON_RUN=1 -u www-data myddleware php /var/www/html/bin/console myddleware:jobScheduler --env=background

monitor:
	@docker-compose -f docker-compose.yml exec myddleware bash /var/www/html/dev/script/monitor.sh

logs: debug
	@docker-compose logs -f myddleware

logs-rotate:
	@docker-compose run --rm --no-deps myddleware bash docker/script/logs-rotate.sh

debug: init
	@docker-compose -f docker-compose.yml -f docker-compose.debug.yml up -d --remove-orphans

prod: init clean-cache fix
	@docker-compose --env-file .env.docker up -d --remove-orphans

start: prod
	@echo ">>> Myddleware is ready."

restart: recreate
	@echo ">>> Myddleware is ready."

fix:
	@docker-compose run --rm myddleware bash docker/script/fix.sh || true

bash:
	@docker-compose -f docker-compose.yml exec myddleware bash

update-secret:
	@bash dev/script/update-secret.sh

generate-template:
	@docker-compose -f docker-compose.yml exec myddleware bash dev/script/generate-template.sh

docker-stop-all:
	@docker stop $$(docker ps -qa | grep -v $${MAKEBAT_CONTAINER_ID:-null}) >/dev/null 2>/dev/null || true

reset: clean
	@bash docker/script/reset.sh

reset-mysql:
	@bash docker/script/reset-mysql.sh

install: init php-install js-install
	@echo "Myddleware installation complete."

## ---
## PHP
## ---
php-install: up
	@docker-compose -f docker/env/script.yml run --rm --no-deps myddleware composer install

php-update: up
	@docker-compose -f docker/env/script.yml run --rm --no-deps myddleware composer update

## ----------
## JavaScript
## ----------
js-install: up
	@docker-compose -f docker-compose.yml -f docker/env/dev.yml run --rm --no-deps myddleware yarn install

js-build: up fix
	@docker-compose -f docker-compose.yml -f docker/env/dev.yml run --rm --no-deps myddleware yarn run build
	@docker-compose run --rm --no-deps -w /var/www/html/public myddleware rsync -Rr ./build/ ./build/

## ------
## Docker
## ------
ps:
	@docker-compose ps

up:
	@docker-compose --env-file .env.docker -f docker-compose.yml up -d

up-mysql:
	@docker-compose --env-file .env.docker -f docker-compose.yml up -d --force-recreate mysql

down:
	@docker-compose down --remove-orphans

build:
	@docker-compose -f docker-compose.yml -f docker/env/dev.yml build myddleware

push:
	@docker login -u opencrmitalia
	@docker build -t opencrmitalia/myddleware:v3 .
	@docker push opencrmitalia/myddleware:v3

pull:
	@docker-compose -f docker-compose.yml pull --include-deps

recreate: init
	@docker-compose -f docker-compose.yml up -d --remove-orphans --force-recreate

## -------
## Develop
## -------
dev: \
	init \
	fix \
	dev-clean \
	dev-up \
	dev-prepare-vtiger \
	dev-prepare-mssql

dev-up: build
	@docker-compose -f docker-compose.yml -f docker/env/dev.yml up --force-recreate -d

dev-clean:
	@docker-compose run --rm myddleware bash -c "cd var/logs; rm -f vtigercrm.log; touch vtigercrm.log; chmod 777 vtigercrm.log"

dev-install: dev-up
	@docker-compose -f docker-compose.yml run --rm myddleware rm -fr vendor
	@docker-compose -f docker-compose.yml run --rm myddleware composer install
	@docker-compose -f docker-compose.yml run --rm myddleware chmod 777 -R vendor

dev-js-install: dev-up
	@docker-compose -f docker-compose.yml -f docker/env/dev.yml run --rm myddleware yarn install
	@docker-compose -f docker-compose.yml run --rm myddleware chmod 777 -R node_modules yarn.lock

dev-prepare-vtiger:
	@docker-compose exec vtiger1 bash dev/script/vtiger-install.sh
	@docker-compose exec vtiger2 bash dev/script/vtiger-install.sh

dev-prepare-mssql:
	@docker-compose exec mssql sqlcmd -S '127.0.0.1' -U 'sa' -P 'Secret.1234!' -Q 'DROP DATABASE IF EXISTS MSSQL;'
	@docker-compose exec mssql sqlcmd -S '127.0.0.1' -U 'sa' -P 'Secret.1234!' -Q 'CREATE DATABASE MSSQL COLLATE Latin1_General_CS_AS;'
	@docker-compose exec mssql sqlcmd -S '127.0.0.1' -U 'sa' -P 'Secret.1234!' -i /fixtures/mssql.sql

dev-create-random-contacts:
	@docker-compose exec vtiger1 php -f dev/script/create-random-contacts.php

## ---
## VPN
## ---
vpn-start:
	@docker-compose --env-file .env.docker up -d --force-recreate vpn

vpn-set-passphrase:
	@docker-compose --env-file .env.docker run --rm --no-deps vpn set_passphrase

vpn-add-client:
	@docker-compose --env-file .env.docker exec vpn add_client $(name)

vpn-get-client:
	@docker-compose --env-file .env.docker exec vpn get_client $(name)

## -------
## Testing
## -------
test-dev: reset install setup dev
	@docker-compose ps

test-debug: reset install setup debug
	@docker-compose ps

test-prod: reset install setup prod
	@docker-compose ps

test-mysql: reset-mysql init up-mysql wait
	@docker-compose -f docker-compose.yml up -d phpmyadmin
	@docker-compose -f docker-compose.yml logs -f mysql

test-backup: up
	@docker-compose -f docker-compose.yml logs -f backup

test-monitoring:
	@docker-compose -f docker-compose.yml exec myddleware rm -f /var/www/html/var/logs/monitoring.log
	@docker-compose -f docker-compose.yml exec myddleware bash /var/www/html/dev/script/monitoring.sh
	@docker-compose -f docker-compose.yml exec myddleware cat /var/www/html/var/logs/monitoring.log
