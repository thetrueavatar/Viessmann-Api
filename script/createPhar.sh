#!/usr/bin/env bash
cd ..
php -d phar.readonly=0 phar-builder.phar package composer.json