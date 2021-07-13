<?php
include __DIR__.'/../index.php';
use Viessmann\API\ViessmannAPI;

$params = parse_ini_file(__DIR__ . "/credentials.properties");
//$params = [
//    "user" => trim("$params[0]"),
//    "pwd" => trim("$params[1]"),
//    "installationId" =>trim("$params[2]"),
//    "gatewayId" =>trim("$params[3]"),
//    "clientId" =>trim("$params[4]"),
//    "deviceId" => "0",
//    "circuitId" => "0"
//];
function print_exception($e){
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "Trace:" . $e->getTraceAsString() . "\n";
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