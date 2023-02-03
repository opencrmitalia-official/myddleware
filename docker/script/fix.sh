#!/usr/bin/env bash
set -e



## Fix permissions problem on `var/cache/` directory
[ ! -d var/cache ] && mkdir -p var/cache
chmod 777 -R var/cache

[ ! -d var/cache/background ] && mkdir -p var/cache/background
chmod 777 -R var/cache/background

## Fix permissions problem on `var/log/` directory
[ ! -d var/log ] && mkdir -p var/log
chmod 777 -R var/log
