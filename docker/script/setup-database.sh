#!/usr/bin/env bash

## Setup database then create admin user
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --append
php bin/console myddleware:add-user admin secret docker@myddleware.com || true

## Old code to promote to ROLE_SUPER_ADMIN
php bin/console myddleware:promote-user docker@myddleware.com ROLE_SUPER_ADMIN || true
