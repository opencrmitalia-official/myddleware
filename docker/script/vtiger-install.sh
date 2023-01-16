#!/usr/bin/env bash

if [ -f docker/script/vtiger-custom-install.sh ]; then
  echo "Vtiger Custom Installation..."
  bash docker/script/vtiger-custom-install.sh
fi

