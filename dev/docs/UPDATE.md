# Aggiornare un istanza Myddleware

Per aggiornare un instanza myddleware bisogna prima di tutto collegarsi 
via SSH alla macchia dove si trova l'applicativo installato, una volta collegati
eseguire i seguenti comandi. (La cartella myhost puù variare da cliente a cliente)

```
sudo su
cd /home/(myhost)/myddleware
make down
git pull
```

Adesso potrebbero apparire dei conflitti o dei blocchi sui file dentro la seguente cartella

```
./src/Myddleware/RegleBundle/Custom/Solutions
```

quindi in questo caso bisogna rinominare il file bloccante, come nell'esempio seguente

```
mv ./src/Myddleware/RegleBundle/Custom/Solutions/oracle.php ./src/Myddleware/RegleBundle/Custom/Solutions/oracle.client.php  
```

viene aggiunto semplicemente la parola '.client' prima dell'estensione '.php'.

A questo punto si deve ripetere almeno due o tre volte il seguente comando

```
git pull
```

Fino a quando il messaggio a video non sarà come questo

```
Already up to date.
```

> **ATTENZIONE:** Se questo messaggio non dovesse apparire, contattre l'assistenza sviluppo per riolvere i conflitti sul progetto.

Se non vi sono stati problemi si proceda con i seguenti passaggi.

Adesso bisogna installare le eventuali nuove librerie rilasciate quindi, va eseguito il seguente comando

```
make update
make setup
```

> **AVVISO:** In qualche caso potrebbero essere segnalati degli errori al database derivati dal tentativo
> che l'applicazione fa di ricreare l'utente 'admin', ignorate questo errore la procedura si completerà ugualmente.

Adesso si puo riportare l'ambiente nella modalità PRODUCTION con il seguente comando

```
make prod
make fix
``` 
