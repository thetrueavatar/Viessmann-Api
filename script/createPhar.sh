#!/usr/bin/env bash
cd ..
php -d phar.readonly=0 phar-builder.phar package -n composer.json
php phpDocumentor.phar -d src/API -t docs/