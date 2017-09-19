#!/usr/bin/env bash

PHPMD_LOGS_DIR=./build/logs/phpmd

echo "Run PHP Mess Detector"

mkdir -p ${PHPMD_LOGS_DIR}

./vendor/bin/phpmd ./ text phpmd.xml --suffixes php
./vendor/bin/phpmd ./ xml phpmd.xml --suffixes php \
    --reportfile ${PHPMD_LOGS_DIR}/report-phpmd.xml

