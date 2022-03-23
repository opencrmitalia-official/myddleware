#!/usr/bin/env bash

echo "===> You are about to erase everything (type in capital letter: YES)?: "

read AGREE

if [ "${AGREE}" = "YES" ]; then
  touch .env.local && docker-compose down -v --remove-orphans && rm -f .env.local
  docker-compose -f docker/env/script.yml run --rm --no-deps myddleware rm -fr vendor node_modules
  docker-compose -f docker/env/script.yml run --rm --no-deps myddleware chmod -R 777 docker/tmp public/build public/js var/cache/prod docker/var/mysql
  mkdir -p docker/var/mysql && touch docker/var/mysql/.gitkeep
  git clean -dfx
else
  echo "Wrong response!"
fi
