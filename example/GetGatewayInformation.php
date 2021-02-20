<?php
include __DIR__.'/bootstrap.php';
echo $viessmannApi->getGatewayWifi()."\n";
echo $viessmannApi->getGatewayBmuconnection()."\n";
echo $viessmannApi->getGatewayDevices()."\n";
echo $viessmannApi->getGatewayFirmware()."\n";
echo $viessmannApi->getGatewayStatus()."\n";


