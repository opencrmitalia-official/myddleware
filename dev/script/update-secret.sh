#!/usr/bin/env bash
set -e

source .env

printf "Current MySQL root password: "
read current_mysql_root_password

docker-compose -f docker-compose.yml up -d

mysql_query="docker-compose exec -e MYSQL_PWD=${current_mysql_root_password} mysql mysql -uroot -e "

echo "Updating MySQL 'myddleware' user"
${mysql_query} "CREATE USER IF NOT EXISTS 'myddleware'@'%' IDENTIFIED BY '$mysql_password';"
${mysql_query} "GRANT ALL ON myddleware.* TO 'myddleware'@'%';"
${mysql_query} "USE mysql; ALTER USER 'myddleware'@'%' IDENTIFIED BY '$mysql_password'; FLUSH PRIVILEGES;"

echo "Updating MySQL 'root' user"
${mysql_query} "USE mysql; ALTER USER 'root'@'%' IDENTIFIED BY '$mysql_root_password'; FLUSH PRIVILEGES;"
docker-compose exec -e MYSQL_PWD=${current_mysql_root_password} mysql mysqladmin -uroot password $mysql_root_password

docker-compose -f docker-compose.yml up -d --force-recreate myddleware mysql
