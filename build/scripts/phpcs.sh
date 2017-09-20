#!/usr/bin/env bash

PHPCS_LOGS_DIR=./build/logs/phpcs

printf -- "\n----------------------"
printf -- "\n Run PHP Code Sniffer "
printf -- "\n----------------------\n"

mkdir -p ${PHPCS_LOGS_DIR}

./vendor/bin/phpcs --standard=./phpcs.xml.dist \
    --report=full \
    --report-checkstyle=${PHPCS_LOGS_DIR}/report-phpcs.checkstyle.xml
