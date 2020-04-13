<?php
require_once '/var/www/html/core/php/core.inc.php';

const CMD_TEMP_EXT = "1021";
const CMD_TEMP_BOIL = "1022";
const CMD_MODE = "1023";

include __DIR__ . '/bootstrap.php';

$virtual = eqLogic::byId(239);

$cmd = cmd::byId(CMD_TEMP_EXT);
$virtual->checkAndUpdateCmd($cmd, $viessmannApi->getOutsideTemperature());

$cmd = cmd::byId(CMD_TEMP_BOIL);
$virtual->checkAndUpdateCmd($cmd, $viessmannApi->getHotWaterStorageTemperature());

$res = $viessmannApi->getActiveMode();
if ($res == "standby"):
    $res = "Arret";
elseif ($res == "dhw"):
    $res = "Eau Chaude";
elseif ($res == "dhwAndHeating"):
    $res = "Eau Chaude + Chauffage";
elseif ($res == "forcedReduced"):
    $res = "Réduit";
elseif ($res == "forcedNormal"):
    $res = "Forcé";
else:
    $res = "Erreur";
endif;
$cmd = cmd::byId(CMD_MODE);
$virtual->checkAndUpdateCmd($cmd, $res);
