# Aggiornare da Myddleware 2 a Myddleware 3

Per aggiornare un instanza myddleware bisogna prima di tutto collegarsi 
via SSH alla macchia dove si trova l'applicativo installato, una volta collegati
eseguire i seguenti comandi. (La cartella myhost puù variare da cliente a cliente)

```
sudo su
cd /home/(myhost)/myddleware
```

## Effettuare il backup 

Se ci sono problemi di spazio sul disco eseguire la fase di "Pulizia del database" prima del backup (se lo si ritiene necessario)
La seguiente lista di comandi permetti di creare una cartella backup con un copia esatta del progetto myddleware per eventuali risoluzioni di problemi

```
cd ..
mkdir backup
root@crmdodic:/home# cp -fr myddleware/ backup/
cd myddleware
```

## Pulizia del database

Il database potrebbe risultare molto pieno quindi i segueti comanti lanciati da phpmyadmin possono rimuovere dati inutili ai fini del processo
di aggiornamento, vi ricordiamo che più dati inutili vi sono nel database più lungo sara il tempo richiesto per l'upgrade.

```
TRUNCATE `Log`
```

## Preparazione ambiente GIT

```
make down
git pull
git commit -am migration
git pull
```

Adesso potrebbero apparire dei conflitti o dei blocchi sui file dentro la seguente cartelle

```
./src/Myddleware/RegleBundle/Custom/Solutions
```

NOTA BETE: La risoluzione dei conflitti deve essere fatta soltato da Sviluppatori esperti

Eseguire il seguente comando 

```
git checkout main
```

Adesso spostiamo i file del database dalla cartella vecchia versione nel nuovo percorso nuova versione

```
cp -fr var/mysql/ docker/var/
```

In caso di problemi di spazio usare il comando `mv` al posto di `cp`

## Installazione delle dipendenze e setup

```
make install
docker-compose up -d
docker-compose ps
```

Verificare i seguenti passaggi

- verificare che tutti i containers siano correttameten in esecuzione (Stato = UP)
- verificare che attraverso phpmyadmin si possa accedere al database

## Migrazione del database

Attraverso PHPMyAdmin eseguire il seguente comando sul database 'myddleware'

```
CREATE USER 'myddleware'@'%' IDENTIFIED BY 'secret';
GRANT CREATE, INDEX, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD on *.* TO 'myddleware'@'%' WITH GRANT OPTION;
```

I seguenti passaggi aggiorneranno il database e lo renderanno utilizzabile per la versione 3 di myddleware
questa operazione è irreversibile.

```
docker-compose exec myddleware php bin/console cache:clear --env=prod 
docker-compose exec myddleware php bin/console cache:clear --env=background 
docker-compose exec myddleware php bin/console doctrine:schema:update --force 
```

Il processo si blocchera mostrando degli errori a questo aprire il link in phpmyadmin e procedere con la rinomina delle tabelle
che iniziano per lettera miuscola e metterele in minuscola ad esempi "Document" diventa "document", "Rule" diventa "rule" etc.. 

```
http://{indirizzo-myddleware}:30088/index.php?route=/table/operations&db=myddleware&table=Document
```

Per verificare se ci sono ancora comandi SQL non eseguiti usare il seguete comando

```
docker-compose exec myddleware php bin/console doctrine:schema:update --dump-sql
```

Rieseguire il comando che avvia nuovamente la migrazione

```
docker-compose exec myddleware php bin/console doctrine:schema:update --force
```

Quando tutte le query di migrazione saranno completate eseguire questi comandi

```
docker-compose exec myddleware php bin/console doctrine:fixtures:load --append
docker-compose exec myddleware php bin/console myddleware:upgrade --env=background 
```

L'ultimo comando potrebbe dare degli errori per preoseguire eseguire questa query su phpmyadmin 

```
UPDATE `job` SET `status` = 'End' WHERE `job`.`status` = 'Start';
```

Eseguire nuovamente questo comando

```
docker-compose exec myddleware php bin/console myddleware:upgrade --env=background
```

Eseguire questo comando per risolvere problemi javascript post-aggiornamento

```
rsync -Rr ./build/ ./build/
```

Adesso si puo riportare l'ambiente nella modalità PRODUCTION con il seguente comando

```
make prod
make fix
``` 

Visitare myddleware al seguente indirizzo:

```
http://{indirizzo-myddleware}:30080
```

## Creare le regole personalizzate

Le regole personalizzate attraverso i file custom devono essere ricreate da un utente esperto di myddleware.

## Pulizia finale

Eseguire i seguenti comandi per liberare spazio occupato da file che ormai non servono più

```
rm -fr var/mysql/
```
