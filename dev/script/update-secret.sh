#!/usr/bin/env bash

source .env

printf "Current MySQL root password: "
read current_mysql_root_password

mysql_query="docker-compose exec -e MYSQL_PWD=${current_mysql_root_password} mysql mysql -uroot -h0.0.0.0 -e "

${mysql_query} "CREATE USER IF NOT EXISTS 'myddleware'@'%' IDENTIFIED BY '$mysql_password';"
${mysql_query} "GRANT ALL ON myddleware.* TO 'myddleware'@'%';"
${mysql_query} "USE mysql; ALTER USER 'root'@'%' IDENTIFIED BY '$mysql_root_password'; FLUSH PRIVILEGES;"
${mysql_query} "USE mysql; ALTER USER 'myddleware'@'%' IDENTIFIED BY '$mysql_password'; FLUSH PRIVILEGES;"
