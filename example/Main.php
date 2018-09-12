<?php
include 'phar://../bin/Viessmann-Api-0.2.phar/index.php';

use Viessmann\API\ViessmannAPI;

$credentials = file("../resources/credentials.properties");
$params = [
    "user" => trim("$credentials[0]", "\n"),
    "pwd" => trim("$credentials[1]", "\n"),
    "uri" => "vicare://oauth-callback/everest"
];
$viessmanApi = new ViessmannAPI($params);
echo $viessmanApi->getFeatures();
echo "Température extérieure " . $viessmanApi->getOutsideTemperature() . "\n";
echo "Température boiler " . $viessmanApi->getBoilerTemperature() . "\n";
echo "Pente " . $viessmanApi->getSlope() . "\n";
echo "Parallèle " . $viessmanApi->getShift() . "\n";
echo "Mode chaudière " . $viessmanApi->getActiveMode() . "\n";
echo "Programme actif " . $viessmanApi->getActiveProgram() . "\n";
echo "Is Heating Burner active ? " . $viessmanApi->isHeatingBurnerActive() . "\n";//in php false bool is converted into empty string
echo "Is Dhw mode active ? " . $viessmanApi->isDhwModeActive() . "\n";
echo "Température de confort " . $viessmanApi->getComfortProgramTemperature() . "\n";
echo "Température écho " . $viessmanApi->getEchoProgramTemperature() . "\n";
echo "Température externe " . $viessmanApi->getExternalProgramTemperature() . "\n";
echo "Température réduit " . $viessmanApi->getReducedProgramTemperature() . "\n";
echo "Température supply " . $viessmanApi->getSupplyProgramTemperature() . "\n";
echo "Est en veille ? " . $viessmanApi->isInStandbyMode() . "\n";
echo "Appelle resources " . $viessmanApi->getRawJsonData(ViessmannAPI::HEATING_PROGRAM_ACTIVE). "\n";
echo "écriture température ecs avec json ".$viessmanApi->setRawJsonData(ViessmannAPI::HEATING_DWH_TEMPERATURE,"setTargetTemperature","{\"temperature\":60.0}"). "\n";
echo "écriture température ecs ".$viessmanApi->setDhwTemperature("58.0"). "\n";
