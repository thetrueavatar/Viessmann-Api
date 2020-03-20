<?php
include __DIR__ . '/bootstrap.php';

use Viessmann\API\ViessmannFeature;

echo $viessmannApi->getRawJsonData(ViessmannFeature::HEATING_GAS_CONSUMPTION_DHW);
echo $viessmannApi->getRawJsonData(ViessmannFeature::HEATING_GAS_CONSUMPTION_HEATING);
echo "Température extérieure " . $viessmannApi->getOutsideTemperature() . "\n";
echo "Température boiler " . $viessmannApi->getBoilerTemperature() . "\n";
echo "Pente " . $viessmannApi->getSlope() . "\n";
echo "Parallèle " . $viessmannApi->getShift() . "\n";
echo "Mode chaudière " . $viessmannApi->getActiveMode() . "\n";
echo "Programme actif " . $viessmannApi->getActiveProgram() . "\n";
echo "Is Heating Burner active ? " . $viessmannApi->isHeatingBurnerActive() . "\n";//in php false bool is converted into empty string
echo "Is Dhw mode active ? " . $viessmannApi->isDhwModeActive() . "\n";
echo "Température de confort " . $viessmannApi->getComfortProgramTemperature() . "\n";
echo "Température écho " . $viessmannApi->getEcoProgramTemperature() . "\n";
echo "Température externe " . $viessmannApi->getExternalProgramTemperature() . "\n";
echo "Température réduit " . $viessmannApi->getReducedProgramTemperature() . "\n";
echo "Température supply " . $viessmannApi->getSupplyProgramTemperature() . "\n";
echo "Est en veille ? " . $viessmannApi->isInStandbyMode() . "\n";
echo "Température eau chaude " . $viessmannApi->getHotWaterStorageTemperature() . "\n";
echo "Appelle resources " . $viessmannApi->getRawJsonData(ViessmannFeature::HEATING_CIRCUITS_0_OPERATING_PROGRAMS_ACTIVE) . "\n";
#echo "écriture température ecs avec json ".$viessmanApi->setRawJsonData(ViessmannFeature::HEATING_DHW_TEMPERATURE,"setTargetTemperature","{\"temperature\":60.0}"). "\n";
#echo "écriture température ecs ".$viessmanApi->setDhwTemperature("58.0"). "\n";