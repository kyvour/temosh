#!/usr/bin/env bash

PHPCS_LOGS_DIR=./build/logs/phpcs

echo "Run PHP Code Sniffer"

mkdir -p ${PHPCS_LOGS_DIR}

./vendor/bin/phpcs --standard=./phpcs.xml \
    --report=full \
    --report-checkstyle=${PHPCS_LOGS_DIR}/report-phpcs.checkstyle.xml
