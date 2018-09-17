<?php
include __DIR__.'/bootstrap.php';
//echo $viessmannApi->getActiveMode();
$viessmannApi->getEcoProgramTemperature();
//echo $viessmannApi->setReducedProgramTemperature("20");
echo $viessmannApi->deActivateEcoProgram();