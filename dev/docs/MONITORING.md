# Monitoraggio

## Passi fondamentali

1. Impostare la mail su cui ricevere le notifiche come campo "Email" dell'utente admin (tipicamente in <http://[mio-indirizzo-ip]:30080/app.php/rule/account/>), dopo cliccare su "Update"
2. Impostare i dati STMP necessari per l'invio della email di notifica, consigliamo di usare il "Transport" sul valore "SMTP" (tipicamente <http://[mio-indirizzo-ip]:30080/app.php/rule/managementsmtp/>)
3. Salvare esclusivamente usando il tasto "Send test mail". ATTENZIONE: Ogni volta che si salvano i dati, la password scompare a video, quindi se si vuole cambiare i dati ed eseguire un secondo salvataggio bisogna re-inserire la password nell'apposito campo
4. Ripetere il "Send test mail" fino a quando non si riceve una mail di conferma che tutto e funzionante.
5. Aprire il gestore file e personalizzazioni (tipicamente allindirizzo <http://[mio-indirizzo-ip]:30090/>), Aprire dentro la castella 'Custom' il file che si chiama 'Custom.json', impostare allinterno di questo file un valore per la chiave "instance_name"


## Monitoraggio errori con scheduler 

Fare riferimento alla guida ufficiale di Myddleware al seugnete link

- http://community.myddleware.com/index.php/administration-guide/#notification-des-erreurs

## Monitoraggio Task non chiusi

Per impostare un tempo minimo per cui ricevere la mail di avvisto che un task non si sia concluso settare dei valori in "minuti" all'interno delle chiavi   "alert_time_limit", "alert_reminder_time" rispettivamente per:

- "alert_time_limit": tempo minimo di attesa necessario per considerare un task come bloccato (60 minuti) 
- "alert_reminder_time": tempo di attesa richisto per inviare un secondo avviso nel caso all'utente sia sfuggito il primo, suggeriamo di mettere questo valore a 360 minuti almeno




