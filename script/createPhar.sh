#!/usr/bin/env bash
cd ..
php -d phar.readonly=0 phar-builder.phar package -n composer.json
vendor/bin/apigen generate src/API --destination docs