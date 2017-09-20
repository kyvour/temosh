#!/usr/bin/env bash

PHPUNIT_LOGS_DIR=./build/logs/phpunit

printf -- "\n--------------------"
printf -- "\n Run PHP Unit tests "
printf -- "\n--------------------\n"

mkdir -p ${PHPUNIT_LOGS_DIR}

./vendor/bin/phpunit \
    --colors=auto \
    --log-junit=${PHPUNIT_LOGS_DIR}/report-phpunit.junit.xml \
    --coverage-clover=${PHPUNIT_LOGS_DIR}/report-coverage.clover.xml \
    --coverage-html=${PHPUNIT_LOGS_DIR}/report-coverage.html \
    --coverage-text
