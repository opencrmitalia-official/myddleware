## Restart

Le seguenti indicazioni sono utili per effetturare un riavvio forzato dell'applicazione 
Myddleware e permettere un normale ritorno online dopo un down derivato da qualche problema imprevisto
Il primo passo consiste nel accedere al server via SSH dentro la cartella in cui si trova l'applicativo, eseguite is sequenti comandi.
Attenzione, la cartella 'myhost' è un segnaposto, vi sarà stata precedentemente indicato il nome corretto della cartella. 

```shell
sudo su
cd /home/(myhost)
cd myddleware
```

Con l'ultimo comando ci troviamo nella cartella del nostro software. Procediamo con l'esecuzione del comando di riavvio. Digitare quanto segue.

```bash
make prod
```

Dopo alcuni secondi, l'applicazione sarà raggiungibile al seguente indirizzo

- <http://[IP-del-server]:30080>
  
