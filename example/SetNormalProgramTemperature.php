<?php
include __DIR__.'/bootstrap.php';
use Viessmann\API\ViessmannAPI;
$viessmanApi->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_NORMAL,"setTemperature","{\"targetTemperature\":20}");