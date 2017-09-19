#!/usr/bin/env bash

# Run PHP Mess Detector.
./build/scripts/phpmd.sh
# Run PHP Copy-Paste Detector.
./build/scripts/phpcpd.sh
# Run PHP Code Sniffer.
./build/scripts/phpcs.sh
# Run PHP Unit tests.
./build/scripts/phpunit.sh
