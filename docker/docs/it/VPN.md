# Abilitare l'utilizzo di VPN (OpenVPN) per accedere a myddleware

Il primo passo è sempre quello di passare in modalità `debug` con il seguente comando

```
make debug
```

Prima bisogna valorizzare le seguenti variabili nel file `.env.docker`

```
external_address=...
openvpn_port=...
```

- `external_address` deve riportare l'IP pubblico della macchia server in cui e installato myddleware
- `openvpn_port` in generale mettere sempre il valore 1194, se questa porta nel server non è usabile, allora chiede supporto avanzato.

Adesso, seguire i seguenti comandi 

```
docker pull javanile/openvpn:2.4
make vpn-start
```

Fare l'inizializzazione del Server OpenVPN con i seguenti comandi

```
make vpn-set-passphrase
```

Seguire la procedura inserendo la passphrase più opportuna tutte le volte che viene chiesta
(NOTA: in questa fase si sta decidendo la passphrase che poi sarà usata in futuro per creare le varie 
istanze dei client VPN che dovranno connettersi, quindi prenderne e salvarla sul Servizio Cloud)

Adesso possiamo creare il primo client

```
make vpn-add-client name=NOMECLIENT
```

con questo comando sarà creato un client verranno chiesti una chiave segrata per lo specifico client e
la passphrase inserita in fase di inizializzazionde del server necessaria affinche si dimostri di avere
i permessi di poter creare utenze in questo server.

Adesso possiamo generare il file `.ovpn` da inviare alla Workstastion o Server remoto che vorra raggiungere Myddleware.
Usare il seguente comando

```
make vpn-get-client name=NOMECLIENT > docker/var/downloads/NOMECLIENT.ovpn
```

Per poter scaricare il file accedere al FileManager web presente con il progetto Myddleware dentro la cartella Downloads,
poi consegnare il file appena creato e la chiava segreta a dovrà connettere il cliente alla VPN.

## Casi particolari

Potrebbe essere necessario fare in modo che Myddleware possa raggiungere applicazioni server che si trovano
in client che si sono connessi alla VPN. Per fare ciò bisogna scrivere una regola dentro la variabile `vpn_client_forward` dentro il file `.env.docker`
Inserire qui l'IP virtuale assegnato al client seguito da ':' e la porta del servizio, ad esempio:

```
vpn_client_forward=192.168.255.6:22
```

dopo che vinene cambiata questa variabile bisogna lanciare il comando

```
make prod
```

per rendere effettiva la modifica

questa riga permettere a myddleware di accedere al servizio FTP presente sul server
usando come HOST la parola `vpn` e come porta la `22`

NOTA: Non usare gli IP virtuali all'interno di Myddleware

## Conclusione

Riportare l'applicazione in modalità produzione con il seguente comando

```shell
make prod
```
