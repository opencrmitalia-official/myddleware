# Crontab

Spesso potrebbe essere necessario schedulare delle operazioni dentro myddleware, ecco alcuni esempi

## Eseguire una regola ogni minuto

Digitare il seguente comando

```
sudo crontab -e
```

Aggiungere la seguente riga e poi salvare

```
* * * * * cd /home/ubuntu/myddleware && docker compose exec myddleware php bin/console myddleware:synchro 6492c01b61102 >> var/log/cron.log 2>&1
```

## Eseguire una query di pulizia ogni minuto

Digitare il seguente comando

```
sudo crontab -e
```

Aggiungere la seguente riga e poi salvare

```
* * * * * cd /home/ubuntu/myddleware && docker compose exec mysql sh -c "MYSQL_PWD=\$MYSQL_ROOT_PASSWORD mysql myddleware -e \"   UPDATE log SET msg = 'Status : New5' WHERE log.id = 1   \";" >> var/log/cron.log 2>&1
```

## Accendere una particolare regola a una certa ora

Digitare il seguente comando

```
sudo crontab -e
```

Aggiungere la seguente riga e poi salvare

```
0 0 * * * cd /home/ubuntu/myddleware && docker compose exec mysql sh -c "MYSQL_PWD=\$MYSQL_ROOT_PASSWORD mysql myddleware -e \"   UPDATE rule SET active = 1 WHERE rule.id = '64108cee04141'   \";" >> var/log/cron.log 2>&1
```

## Spegnere una particolare regola a una certa ora

Digitare il seguente comando

```
sudo crontab -e
```

Aggiungere la seguente riga e poi salvare

```
0 1 * * * cd /home/ubuntu/myddleware && docker compose exec mysql sh -c "MYSQL_PWD=\$MYSQL_ROOT_PASSWORD mysql myddleware -e \"   UPDATE rule SET active = 0 WHERE rule.id = '64108cee04141'   \";" >> var/log/cron.log 2>&1
```

## Eseguire le regole soltanto di notte

Per procedere con questo speciale caso, di dovrà spegnere lo scheduler Synchro ALL dentro Myddleware ed inserirlo al livello di sistema

```
sudo crontab -e
```

Aggiungere la seguente riga che fara la synchro ALL dalle 10 della sera alle 5 del mattino (al netto della timezone) 

```
*/5 22,23,0,1,2,3,4,5 * * * cd /home/ubuntu/myddleware && docker compose exec myddleware php bin/console myddleware:synchro ALL >> var/log/cron.log 2>&1
```

Potrebbe essere utile il caso inverso, ovvero fare girare le regole di giorno tra le 5.00 fino alle 20.00, ecco come fare

```
*/5 5-20 * * * cd /home/ubuntu/myddleware && docker compose exec myddleware php bin/console myddleware:synchro ALL >> var/log/cron.log 2>&1
```

## Creare script complessi che eseguiranno operazioni multiple

Digitare il seguente comando

```
sudo crontab -e
```

Aggiungere la seguente riga e poi salvare

```
0 1 * * * bash /etc/myddleware-scripts/mio-script.sh >> var/log/cron.log 2>&1
```

Ecco come dovrebbe presentarti lo script

```shell
#!/bin/bash
set -e

## File: /etc/myddleware-scripts/mio-script.sh

## IMPORTANTE!!!
## La prima cosa da fare è entrare nella cartella di myddleware
## Se non lo fai non potrai eseguire i comandi
cd /home/ubuntu/myddleware

## Eseguire una query 
docker compose exec mysql sh -c "MYSQL_PWD=\$MYSQL_ROOT_PASSWORD mysql myddleware -e \"   UPDATE rule SET active = 0 WHERE rule.id = '64108cee04141'   \";"

## Sincronizzo una regola
docker compose exec myddleware php bin/console myddleware:synchro 6492c01b61102
```
