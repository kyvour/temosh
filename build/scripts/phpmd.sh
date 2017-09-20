#!/usr/bin/env bash

PHPMD_LOGS_DIR=./build/logs/phpmd

printf -- "\n-----------------------"
printf -- "\n Run PHP Mess Detector "
printf -- "\n-----------------------\n"

mkdir -p ${PHPMD_LOGS_DIR}

./vendor/bin/phpmd ./ text phpmd.xml.dist --suffixes php
./vendor/bin/phpmd ./ xml phpmd.xml.dist --suffixes php \
    --reportfile ${PHPMD_LOGS_DIR}/report-phpmd.xml

