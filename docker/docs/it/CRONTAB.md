# Crontab

Spesso potrebbe essere necessario schedulare delle operazioni dentro myddleware, ecco alcuni esempi

## Eseguire una regola ogni minuto

Digitare il seguente comando

```
crontab -e
```

Aggiungere la seguente riga e poi salvare

```
* * * * * cd /home/ubuntu/myddleware && docker compose exec myddleware php -d xdebug.mode=off bin/console myddleware:synchro 6492c01b61102 >> var/log/cron.log 2>&1
```

## Eseguire una query di pulizia ogni minuto

Digitare il seguente comando

```
crontab -e
```

Aggiungere la seguente riga e poi salvare

```
* * * * * cd /home/ubuntu/myddleware && docker compose exec mysql sh -c "MYSQL_PWD=\$MYSQL_ROOT_PASSWORD mysql myddleware -e \"   UPDATE log SET msg = 'Status : New5' WHERE log.id = 1   \";" >> var/log/cron.log 2>&1
```

## Accendere una particolare regola a una certa ora

Digitare il seguente comando

```
crontab -e
```

Aggiungere la seguente riga e poi salvare

```
0 0 * * * cd /home/ubuntu/myddleware && docker compose exec mysql sh -c "MYSQL_PWD=\$MYSQL_ROOT_PASSWORD mysql myddleware -e \"   UPDATE rule SET active = 1 WHERE rule.id = '64108cee04141'   \";"
```

## Spegnere una particolare regola a una certa ora

Digitare il seguente comando

```
crontab -e
```

Aggiungere la seguente riga e poi salvare

```
0 1 * * * cd /home/ubuntu/myddleware && docker compose exec mysql sh -c "MYSQL_PWD=\$MYSQL_ROOT_PASSWORD mysql myddleware -e \"   UPDATE rule SET active = 0 WHERE rule.id = '64108cee04141'   \";"
```
