# Monitoraggio

## Passi fondamentali

1. Impostare la mail su cui ricevere le notifiche come campo "Email" dell'utente admin (tipicamente in <http://[mio-indirizzo-ip]:30080/app.php/rule/account/>), dopo cliccare su "Update"
2. Impostare i dati STMP necessari per l'invio della email di notifica, consigliamo di usare il "Transport" sul valore "SMTP" (tipicamente <http://[mio-indirizzo-ip]:30080/app.php/rule/managementsmtp/>)
3. Salvare esclusivamente usando il tasto "Send test mail". ATTENZIONE: Ogni volta che si salvano i dati, la password scompare a video, quindi se si vuole cambiare i dati ed eseguire un secondo salvataggio bisogna re-inserire la password nell'apposito campo
4. Ripetere il "Send test mail" fino a quando non si riceve una mail di conferma che tutto e funzionante.

