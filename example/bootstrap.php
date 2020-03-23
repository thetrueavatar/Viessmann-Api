<?php

use Viessmann\API\ViessmannAPI;
use Viessmann\API\ViessmannApiException;

include 'phar://' . __DIR__ . '/Viessmann-Api-1.3.3.phar/index.php';


$credentials = file(__DIR__ . "/credentials.properties");
$params = [
    "user" => trim("$credentials[0]"),
    "pwd" => trim("$credentials[1]"),
    "installationId" =>trim("$credentials[2]"),
    "gatewayId" =>trim("$credentials[3]"),
    "deviceId" => "0",
    "circuitId" => "0"
];
function print_exception($e){
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
//    echo "Trace:" . $e->getTraceAsString() . "\n";
}
;
$errorHandler= function($e)
{
    $currentException=$e;
    do {
        print_exception($currentException);
    } while ($currentException = $currentException->getPrevious());
};

set_exception_handler($errorHandler);
try {
    $viessmannApi = new ViessmannAPI($params);
} catch (ViessmannApiException $e) {
    $errorHandler($e);
    exit();
}