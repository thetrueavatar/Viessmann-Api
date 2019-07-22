<?php
include __DIR__ . '/bootstrap.php';
print_r( $viessmannApi->getHeatingPowerConsumption('day') );
