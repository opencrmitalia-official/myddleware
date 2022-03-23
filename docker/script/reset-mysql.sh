#!/usr/bin/env bash

echo "===> You are about to erase current database (type in capital letter: YES)?: "

read AGREE

if [ "${AGREE}" = "YES" ]; then
  touch .env.local && docker-compose down -v --remove-orphans && rm -f .env.local
  docker-compose -f docker/env/script.yml run --rm --no-deps myddleware rm -fr docker/var/mysql
  docker-compose -f docker/env/script.yml run --rm --no-deps myddleware chmod -R 777 docker/var
  mkdir -p docker/var/mysql && touch docker/var/mysql/.gitkeep
else
  echo "Wrong response!"
fi
