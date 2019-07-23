<?php
include __DIR__ . '/bootstrap.php';
print_r( $viessmannApi->getHeatingSolarPowerProduction('day') );