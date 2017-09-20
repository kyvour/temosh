#!/usr/bin/env bash

PHPMETRICS_LOGS_DIR=./build/logs/phpmetrics

printf -- "\n--------------------------"
printf -- "\n Run PHP Metrics analyzer "
printf -- "\n--------------------------\n"

mkdir -p ${PHPMETRICS_LOGS_DIR}

./vendor/bin/phpmetrics \
    --offline \
    --report-html=${PHPMETRICS_LOGS_DIR}/report-phpmetrics.html \
    --report-xml=${PHPMETRICS_LOGS_DIR}/report-phpmetrics.xml \
    --extensions="php" \
    --excluded-dirs="(vendor|tests)" \
    ./
