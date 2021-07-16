<?php

use Viessmann\API\ViessmannAPI;
use Viessmann\API\ViessmannApiException;
session_start();
include 'phar://' . __DIR__ . '/Viessmann-Api-2.1.0-SNAPSHOT.phar/index.php';


$params = parse_ini_file(__DIR__ . "/credentials.properties");
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