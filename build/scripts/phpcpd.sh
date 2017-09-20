#!/usr/bin/env bash

PHPCPD_LOGS_DIR=./build/logs/phpcpd

printf -- "\n-----------------------------"
printf -- "\n Run PHP Copy-Paste Detector "
printf -- "\n-----------------------------\n"

mkdir -p ${PHPCPD_LOGS_DIR}

./vendor/bin/phpcpd --log-pmd=${PHPCPD_LOGS_DIR}/report-phpcpd.pmd.xml \
    --exclude=vendor \
    --exclude=tests \
    --fuzzy \
    ./
