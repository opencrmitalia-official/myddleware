# Docker 

This project is based on Docker to crete a flexible runtime environment with all system requirements inside.

There are different files to manage Docker aspects on this project.

## The `Dockerfile`

> Open: <https://github.com/opencrmitalia-official/myddleware/blob/stable/Dockerfile>

This is the principle and unique Dockerfile used to create an PHP/Apache server for run the Myddleware container with the application.
It is organized into section which layer by layer create a Linux machine with the abilty to connect on every kind of Database driver: MSSQL, OracleDB, etc...
This file is organized into layers:

- Configure standard PHP
- Install Xdebug
- Install MS Database
- Install Oracle Database
- Install Platform.sh tool
- Setup Cronjob
- Install DBLIB
- Sysadmin tools
- Entrypoint

## The `docker-compose.yml`

> Open: <https://github.com/opencrmitalia-official/myddleware/blob/stable/docker-compose.yml>

This is the file that run multiple containers, as expected Myddleware is not only one container but it use additional container as follow:

- `myddleware` - The PHP/Apache server that run Myddleware source
- `mysql` - A standard MySQL server used as principal database for Myddleware
- `phpmyadmin` - A ready-to-use tool to manage and inspect Myddleware Database
- `backup` - A ready-to-use tool to create and store remotely the Database backup
- `filebrowser` - A ready-to-use tool to explore files on server used to watch logs or customize solutions
- `vpn` - A VPN container used to connect remote or private database from a LAN into cloud and join it to Myddleware

## The `docker-compose.override.yml`

> Open: <https://github.com/opencrmitalia-official/myddleware/blob/stable/docker-compose.override.yml>

This file is used on DEVELOPMENT for creating fake services to syncronize using Myddleware. 
It is most important to have Microsoft Dabatase with fake data to test multiple aspect of the software

## The `docker-compose.debug.yml`

> Open: <https://github.com/opencrmitalia-official/myddleware/blob/stable/docker-compose.debug.yml>

This file is used to debug a client instance, this add special values for environment variables used to switch on deep logs and messages

## The `Makefile`

> Open: <https://github.com/opencrmitalia-official/myddleware/blob/stable/Makefile>

This file is used to call in short command all docker commands. Just to have small command like `make up` instead of `docker-compose up -d`
