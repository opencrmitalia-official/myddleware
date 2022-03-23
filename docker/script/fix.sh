#!/usr/bin/env bash
set -e

[ ! -d var/cache ] && mkdir -p var/cache
chmod 777 -R var/cache

[ ! -d var/log ] && mkdir -p var/log
chmod 777 -R var/log
