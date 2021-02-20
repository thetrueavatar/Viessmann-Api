<?php
const JEEDOM_IP = "192.168.0.6:32080";
const JEEDOM_API_KEY = "";
const CMD_ID = "1022";
const CMD_ID1 = "1024";
const CMD_ID2 = "1027";
const CMD_ID3 = "1028";
const CMD_ID4 = "1018";
const CMD_ID5 = "1019";
const CMD_ID6 = "1021";
const CMD_ID7 = "1023";
include __DIR__ . '/bootstrap.php';

function post_request($url, array $params)
{
    $query_content = http_build_query($params);
    $fp = fopen($url, 'r', FALSE, // do not use_include_path
        stream_context_create([
            'http' => [
                'header' => [ // header array does not need '\r\n'
                    'Content-type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($query_content)
                ],
                'method' => 'POST',
                'content' => $query_content
            ]
        ]));
    if ($fp === FALSE) {
        fclose($fp);
        return json_encode(['error' => 'Failed to get contents...']);
    }
    $result = stream_get_contents($fp); // no maxlength/offset
    fclose($fp);
    return $result;
}

function jeedom_post($cmd_id, $value)
{
    $jeedom_url_retour = "http://" . JEEDOM_API . "/core/api/jeeApi.php?plugin=virtual&apikey={" . JEEDOM_API_KEY . "}&type=virtual&id={$cmd_id}&value={$value}";
    $jeedom_params = array('key1' => '', 'key2' => '');
    $result = post_request($jeedom_url_retour, $jeedom_params);
    return $result;
}

$res = $viessmannApi->getActiveMode();
if ($res == "standby"):
    $res = "Arret";
elseif ($res == "dhw"):
    $res = "EauChaude";
elseif ($res == "dhwAndHeating"):
    $res = "EauChaude+Chauffage";
elseif ($res == "forcedReduced"):
    $res = "Réduit";
elseif ($res == "forcedNormal"):
    $res = "Force";
else:
    $res = "Erreur";
endif;
jeedom_post("" . CMD_ID4 . "", $res);

$res = $viessmannApi->getActiveProgram();
if ($res == "reduced"):
    $res = "Reduit";
elseif ($res == "normal"):
    $res = "Normal";
elseif ($res == "dhwAndHeating"):
    $res = "EauChaude+Chauffage";
elseif ($res == "forcedReduced"):
    $res = "ForceReduit";
elseif ($res == "forcedNormal"):
    $res = "ForceNormal";
endif;
jeedom_post( CMD_ID5, $res);
jeedom_post(CMD_ID6, (int)$viessmannApi->isHeatingBurnerActive());
jeedom_post(CMD_ID7, $viessmannApi->getHotWaterStorageTemperature());
