<?php
include __DIR__.'/bootstrap.php';
$viessmannApi->startOneTimeDhwCharge();
echo (int)$viessmannApi->isOneTimeDhwCharge();
$viessmannApi->startOneTimeDhwCharge();
echo (int)$viessmannApi->isOneTimeDhwCharge();