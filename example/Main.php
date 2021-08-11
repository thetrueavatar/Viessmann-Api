<?php
include __DIR__ . '/bootstrap-dev.php';

use Viessmann\API\ViessmannFeature;
echo "All datas". $viessmannApi->getRawJsonData("gateway.devices"). "\n";
echo "Température extérieure " . $viessmannApi->getOutsideTemperature() . "\n";
echo "Température boiler " . $viessmannApi->getBoilerTemperature() . "\n";
//echo "Pente " . $viessmannApi->getSlope() . "\n";
//echo "Parallèle " . $viessmannApi->getShift() . "\n";
echo "Mode chaudière " . $viessmannApi->getActiveMode() . "\n";
echo "Programme actif " . $viessmannApi->getActiveProgram() . "\n";
echo "Is Heating Burner active ? " . (int) $viessmannApi->isHeatingBurnerActive() . "\n";//in php false bool is converted into empty string
echo "Is Dhw mode active ? " . (int)$viessmannApi->isDhwModeActive() . "\n";
echo "Température de confort " . $viessmannApi->getComfortProgramTemperature() . "\n";
echo "Température écho " . $viessmannApi->getEcoProgramTemperature() . "\n";
echo "Température externe " . $viessmannApi->getExternalProgramTemperature() . "\n";
echo "Température réduit " . $viessmannApi->getReducedProgramTemperature() . "\n";
echo "Est en veille ? " . (int)$viessmannApi->isInStandbyMode() . "\n";
echo "Température eau chaude " . $viessmannApi->getHotWaterStorageTemperature() . "\n";
