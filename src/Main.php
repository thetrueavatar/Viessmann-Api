<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 30/08/18
 * Time: 15:46
 */

require __DIR__ . '/../vendor/autoload.php';
use Viessmann\API\ViessmannAPI;
$credentials = file("../resources/credentials.properties");
$params=[
    "user"=>trim("$credentials[0]","\n"),
    "pwd"=>"$credentials[1]",
    "uri"=>"vicare://oauth-callback/everest"
];
$viessmanApi=new ViessmannAPI($params);
    echo $viessmanApi->getFeatures();
    echo "Température extérieure ".$viessmanApi->getOutsideTemperature()."\n";
    echo "Température boiler ".$viessmanApi->getBoilerTemperature()."\n";
    echo "Pente ".$viessmanApi->getSlope()."\n";
    echo "Parallèle ".$viessmanApi->getShift()."\n";
    echo "Mode chaudière ".$viessmanApi->getActiveMode()."\n";
    echo "Programme actif ".$viessmanApi->getActiveProgram()."\n";
    echo "Is Heating Burner active ? ".$viessmanApi->isHeatingBurnerActive()."\n";//in php false bool is converted into empty string
    echo "Is Dhw mode active ? ".$viessmanApi->isDhwModeActive()."\n";
    echo "Température de confort ".$viessmanApi->getComfortProgramTemperature()."\n";
    echo "Température écho ".$viessmanApi->getEchoProgramTemperature()."\n";
    echo "Température externe ".$viessmanApi->getExternalProgramTemperature()."\n";
    echo "Température réduit ".$viessmanApi->getReducedProgramTemperature()."\n";
    echo "Température supply ".$viessmanApi->getSupplyProgramTemperature()."\n";
    echo "Est en veille ? ".$viessmanApi->isInStandbyMode()."\n";
    echo "Appelle resources ".$viessmanApi->getRawData(ViessmannAPI::HEATING_PROGRAM_ACTIVE);