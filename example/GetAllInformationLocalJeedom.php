<?php
require_once '/var/www/html/core/php/core.inc.php';

const ID_VIRTUEL = "239"; //indiquer ici l'ID du virtuel, exemple : http://jeedom/index.php?v=d&m=virtual&p=virtual&id=239
const CMD_TEMP_EXT = "1021"; //indiquer l'ID de chaque info/commande comme listé dans http://jeedom/index.php?v=d&m=virtual&p=virtual&id=239#commandtab
const CMD_TEMP_BOIL = "1022";
const CMD_MODE = "1023";

include __DIR__ . '/bootstrap.php';

$virtual = eqLogic::byId(ID_VIRTUEL);

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
    $res = "Nuit";
elseif ($res == "forcedNormal"):
    $res = "Jour";
else:
    $res = "Erreur";
endif;
$cmd = cmd::byId(CMD_MODE);
$virtual->checkAndUpdateCmd($cmd, $res);
