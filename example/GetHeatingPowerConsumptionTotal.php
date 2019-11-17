<?php
include __DIR__ . '/bootstrap.php';
echo "Day:\n";
echo "====\n";
print_r( $viessmannApi->getHeatingPowerConsumptionTotal('day') );
echo "\n";

echo "Week:\n";
echo "=====\n";
print_r( $viessmannApi->getHeatingPowerConsumptionTotal('week') );
echo "\n";

echo "Month:\n";
echo "======\n";
print_r( $viessmannApi->getHeatingPowerConsumptionTotal('month') );
echo "\n";

echo "Year:\n";
echo "=====\n";
print_r( $viessmannApi->getHeatingPowerConsumptionTotal('year') );
echo "\n";