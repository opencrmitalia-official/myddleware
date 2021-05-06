# Testing

Il testing consiste nel installare l'applicazione su un pc local e renderla facilmente operativa per poter fare dei test funzionali
Affinche tutto vada a buon fine il pc deve avere installate le seguenti versione dei software in elenco

- Git
- Docker 18 o superiore
- Docker Compose 1.17 o superiore

## Iniziamo

Il primo passo consiste nel clonare il progetto sul pc dentro la cartella più opportuna, eseguite is sequenti comandi

```bash
git clone https://github.com/opencrmitalia-official/myddleware.git
cd myddleware
```

Con l'ultimo comando ci troviamo nella cartella del nostro software appena scaricato. Procediamo con l'installazione
eseguite il comando,

```bash
make install
```

Adesso bisogna modificare il file `.env` ed inserire una variabile con il valore della chiave segreta di sviluppo come in esempio

```dotenv
gitlab_private_token=...chiave-segreta...
```

Adesso eseguire il comando per la creazione dei dati nel database

```bash
make setup
```

Adesso l'applicazione sara correttamente installata per accedere usate le seguenti istruzioni

- Visitare la pagina <http://localhost:30080>
- Usate le seguenti credenziali: admin/admin

Adesso bisogna mettere la applicazione in modalità DEVELOPMENT

```bash
make dev
```

La modalità DEV appena avviata rende disponibili i seguenti strumenti per effettuare i test:

1. Il FileBrowser: accessibile all'indirizzo <http://localhost:30090> (admin/admin), questo permette una facile access ai LOG e alle configurazioni speciali
2. PhpMyAdmin per modificare il database di Myddleware indirizzo: <http://localhost:30088>
3. CRM Demo N1 con: http://localhost:30081 e phpmyadmin a http://localhost:30091
4. CRM Demo N2 con: http://localhost:30082 e phpmyadmin a http://localhost:30092
5. Adminer per gestire tutto ma specialmente il db Microsoft http://localhost:30099
6. Database Microsoft SQL

Per accedere al DB Microsoft da Adminer usare i seguenti dati

- Tipo: MS SQL (beta)
- Host: mssql (va scritto minuscolo)
- Username: sa
- Password: Secret.1234!
- Database: lasciare vuoto

A questo punto creare sempre dei connettori myddleware verso le sorgeti di prova

Vtiger #1
- Username: admin
- Access Key: va recuperata facendo il login in <http://localhost:30081>
- Url: http://vtiger1 

Vtiger #2
- Username: admin
- Access Key: va recuperata facendo il login in <http://localhost:30082>
- Url: http://vtiger2

Connettore verso il database Microsoft
- Utente: sa
- Password: Secret.1234!
- Host Server: mssql
- Database name: MSSQL
- Porta: 1433

## Possibili scenari di test

Per assicurarsi che il connettore funziona bisogna fare le seguenti prove

- Test inserimento righe inventario		
- Test inserimento righe con campi advanced inventory		
- Test inserimento picklist su righe inventario		
- Test inserimento campi relazionati su righe inventario		
- Test aggiornamento righe inventario		
- Test aggiornamento con campi advanced inventory		
- Test aggiornamento picklist su righe inventario		
- Test aggiornamento campi relazionati su righe inventario		
- Test inserimento nuovo valore nelle picklist

