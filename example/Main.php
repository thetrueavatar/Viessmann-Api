<?php
include __DIR__.'/bootstrap.php';
echo $viessmannApi->getFeatures();
echo "Température extérieure " . $viessmannApi->getOutsideTemperature() . "\n";
echo "Température boiler " . $viessmannApi->getBoilerTemperature() . "\n";
echo "Pente " . $viessmannApi->getSlope() . "\n";
echo "Parallèle " . $viessmannApi->getShift() . "\n";
echo "Mode chaudière " . $viessmannApi->getActiveMode() . "\n";
echo "Programme actif " . $viessmannApi->getActiveProgram() . "\n";
echo "Is Heating Burner active ? " . $viessmannApi->isHeatingBurnerActive() . "\n";//in php false bool is converted into empty string
echo "Is Dhw mode active ? " . $viessmannApi->isDhwModeActive() . "\n";
echo "Température de confort " . $viessmannApi->getComfortProgramTemperature() . "\n";
echo "Température écho " . $viessmannApi->getEchoProgramTemperature() . "\n";
echo "Température externe " . $viessmannApi->getExternalProgramTemperature() . "\n";
echo "Température réduit " . $viessmannApi->getReducedProgramTemperature() . "\n";
echo "Température supply " . $viessmannApi->getSupplyProgramTemperature() . "\n";
echo "Est en veille ? " . $viessmannApi->isInStandbyMode() . "\n";
echo "Appelle resources " . $viessmannApi->getRawJsonData(ViessmannAPI::HEATING_PROGRAM_ACTIVE). "\n";
#echo "écriture température ecs avec json ".$viessmanApi->setRawJsonData(ViessmannAPI::HEATING_DWH_TEMPERATURE,"setTargetTemperature","{\"temperature\":60.0}"). "\n";
#echo "écriture température ecs ".$viessmanApi->setDhwTemperature("58.0"). "\n";