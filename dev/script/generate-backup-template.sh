#!/usr/bin/env bash

## General command
# php bin/console myddleware:generateTemplate <template_name> < descriptionTemplate> <rulesId> --env=background

## microsoftsql_opencrmitalia_template_gestionali (v1)
#php bin/console myddleware:generateTemplate "temp_template_gestionali" "Connessione con gestionali" "6188fa2ded232,6188fb2042051,6188fcc60dd12,6188fda3ef1a0,6188ffd300458,6189007e3636a,618901343fd8f,618a26ca31aca,618a42bcf1f58,618a48db15924,618a511ec0b54,618a516a909bc,618a51bfa20e5,618a52226c02f,618a7c75ce773,618a7e7fd235d" --env=background

## microsoftsql_opencrmitalia_template_gestionali (corrente)
#php bin/console myddleware:generateTemplate "microsoftsql_opencrmitalia_template_gestionali" "Connessione con gestionali" "6188fa2ded232,6188fb2042051,6188fcc60dd12,6188fda3ef1a0,6188ffd300458,6189007e3636a,618901343fd8f,618a26ca31aca,618a42bcf1f58,618a48db15924,618a511ec0b54,618a516a909bc,618a51bfa20e5,618a52226c02f,618a7c75ce773,618a7e7fd235d,61c1ad7622b91,61c1ae56e6e18" --env=background

## Backup
php bin/console myddleware:generateTemplate "microsoftsql_vtigercustom_template_backup" "Connessione SQL Server to CRM" "62150ba0c39ad,62150ba0de02a,62150ba0ecb92,62150ba10d71d,62150ba15995b,62150ba1828b9,62150ba1a388a,62150ba1d6e3f,62150ba212a95,62150ba240e09,62150ba27868f,62150ba2aeb92,62150ba2e4012,62150ba2e77c9,62150ba37171c,624318a672832,6245a3d2c0d0a,6245a6ce2f569,6245ac8f9b8ac,6245b1770d97e" --env=background
php bin/console myddleware:generateTemplate "vtigercustom_microsoftsql_template_backup" "Connessione CRM to SQL Server" "62150ba0c39ad,62150ba0de02a,62150ba0ecb92,62150ba10d71d,62150ba15995b,62150ba1828b9,62150ba1a388a,62150ba1d6e3f,62150ba212a95,62150ba240e09,62150ba27868f,62150ba2aeb92,62150ba2e4012,62150ba2e77c9,62150ba37171c,624318a672832,6245a3d2c0d0a,6245a6ce2f569,6245ac8f9b8ac,6245b1770d97e" --env=background
