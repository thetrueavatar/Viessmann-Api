<?php
include 'phar://' . __DIR__ . '/Viessmann-Api-0.3.phar/index.php';

use Viessmann\API\{ViessmannAPI, ViessmannApiException};

$credentials = file(__DIR__."/credentials.properties");
$params = [
    "user" => trim("$credentials[0]", "\n"),
    "pwd" => trim("$credentials[1]", "\n"),
    "deviceId" => "0",
    "circuitId" => "0"
];
try {
$viessmannApi = new ViessmannAPI($params);
} catch (ViessmannApiException $e) {
    echo $e->getMessage();
    exit();
}