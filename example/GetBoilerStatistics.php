<?php
include __DIR__ . '/bootstrap.php';
echo $viessmannApi->getHeatingBurnerStatistics("hours");
echo $viessmannApi->getHeatingBurnerStatistics("starts");