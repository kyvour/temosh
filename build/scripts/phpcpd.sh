#!/usr/bin/env bash

PHPCPD_LOGS_DIR=./build/logs/phpcpd

echo "Run PHP Copy-Paste Detector"

mkdir -p ${PHPCPD_LOGS_DIR}

./vendor/bin/phpcpd --log-pmd=${PHPCPD_LOGS_DIR}/report-phpcpd.pmd.xml \
    --exclude=vendor \
    --exclude=tests \
    --fuzzy \
    ./
