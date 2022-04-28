#!/usr/bin/env bash

myddleware_url=http://localhost:30080

token=$(curl -s --location --request POST "${myddleware_url}/app.php/api/v1_0/login_check" \
     --header 'Content-Type: application/json' \
     --data-raw "{\"username\":\"admin\",\"password\":\"admin\"}" | cut -d'"' -f4)

echo "--> TOKEN: $token"

synchro=$(curl -s --location --request POST "${myddleware_url}/app_dev.php/api/v1_0/synchro" \
     --header 'Content-Type: application/json' \
     --header "Authorization: Bearer ${token}" \
     --form 'rule=xxxxxxx')

echo "--> SYNCHRO: $synchro"
