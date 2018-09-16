<?php
include __DIR__.'/bootstrap.php';
use Viessmann\API\ViessmannAPI;
$viessmannApi->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_NORMAL,"setTemperature","{\"targetTemperature\":20}");