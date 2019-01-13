<?php
include __DIR__ . '/bootstrap.php';
echo $viessmannApi->getDhwGasConsumption("day");
echo $viessmannApi->getDhwGasConsumption("month");
echo $viessmannApi->getDhwGasConsumption("week");
echo $viessmannApi->getDhwGasConsumption("year");
echo $viessmannApi->getHeatingGasConsumption("day");
echo $viessmannApi->getHeatingGasConsumption("month");
echo $viessmannApi->getHeatingGasConsumption("week");
echo $viessmannApi->getHeatingGasConsumption("year");