<?php
include __DIR__.'/bootstrap.php';
if (isset($argv)) {
    foreach ($argv as $arg) {
        echo "arg ".$arg;
        $argList = explode('=', $arg);
        if (isset($argList[0]) && isset($argList[1])) {
            $_GET[$argList[0]] = $argList[1];
            echo "argList ".$argList[1];
        }
    }
}
$viessmannApi->setNormalProgramTemperature($argv[1]);