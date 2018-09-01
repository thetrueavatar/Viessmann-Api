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
echo "Température extérieure ".$viessmanApi->getOutsideTemperature()."\n";
echo "Température boiler ".$viessmanApi->getBoilerTemperture()."\n";
echo "Pente ".$viessmanApi->getSlope()."\n";
echo "Parallèle ".$viessmanApi->getShift()."\n";
echo "Mode chaudière ".$viessmanApi->getActiveMode()."\n";
echo "Programme actif ".$viessmanApi->getActiveProgram()."\n";