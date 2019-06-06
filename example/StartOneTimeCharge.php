<?php
include __DIR__.'/bootstrap.php';
$viessmannApi->startOneTimeDhwCharge();
echo "".$viessmannApi->isOneTimeDhwCharge();
$viessmannApi->startOneTimeDhwCharge();
echo "".$viessmannApi->isOneTimeDhwCharge();