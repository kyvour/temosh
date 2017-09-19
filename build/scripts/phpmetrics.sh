#!/usr/bin/env bash

echo "Run PHP Metrics analyzer"

PHPMETRICS_LOGS_DIR=./build/logs/phpmetrics

mkdir -p ${PHPMETRICS_LOGS_DIR}

./vendor/bin/phpmetrics \
    --offline \
    --report-html=${PHPMETRICS_LOGS_DIR}/report-phpmetrics.html \
    --report-xml=${PHPMETRICS_LOGS_DIR}/report-phpmetrics.xml \
    --extensions="php" \
    --excluded-dirs="(vendor|tests)" \
    ./
