<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 30/08/18
 * Time: 15:46
 */

require __DIR__ . '/../vendor/autoload.php';
use Viessman\API\ViessmanAPI;
$credentials = file("../resources/credentials.properties");
$params=[
    "user"=>trim("$credentials[0]","\n"),
    "pwd"=>"$credentials[1]",
    "uri"=>"vicare://oauth-callback/everest"
];
$viessmanApi=new ViessmanAPI($params);
echo $viessmanApi->getOutsideTemperature();
