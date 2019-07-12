<?php
include __DIR__.'/bootstrap.php';
$slop=$viessmannApi->getSlope();
echo $viessmannApi->setCurve($argv[1], $slope);