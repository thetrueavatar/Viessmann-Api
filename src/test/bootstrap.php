<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/bootstrap.php';
use Viessmann\API\ViessmannAPI;
$credentials = file(__DIR__."/../../example/credentials.properties");
$params = [
    "user" => trim("$credentials[0]", "\n"),
    "pwd" => trim("$credentials[1]", "\n"),
    "uri" => "vicare://oauth-callback/everest"
];
$viessmannApi = new ViessmannAPI($params);