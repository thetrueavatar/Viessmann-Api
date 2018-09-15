<?php
include 'phar://'.__DIR__.'/Viessmann-Api-0.3-SNAPSHOT.phar/index.php';

use Viessmann\API\ViessmannAPI;

$credentials = file(__DIR__."/credentials.properties");
$params = [
    "user" => trim("$credentials[0]", "\n"),
    "pwd" => trim("$credentials[1]", "\n"),
    "uri" => "vicare://oauth-callback/everest"
];
$viessmanApi = new ViessmannAPI($params);
echo $viessmanApi->getOutsideTemperature();