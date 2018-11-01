<?php
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
    $jeedom_apikey = "SKqLrR6l8T7CB4REvAEj7DHKfrJHa0PM";
    $jeedom_url_retour = "http://192.168.0.2/core/api/jeeApi.php?plugin=virtual&apikey={$jeedom_apikey}&type=virtual&id={$cmd_id}&value={$value}";
    $jeedom_params = array('key1' => '', 'key2' => '');
    $result = post_request($jeedom_url_retour, $jeedom_params);
    return $result;
}

jeedom_post("1022", $viessmannApi->getOutsideTemperature());

jeedom_post("1024", $viessmannApi->getBoilerTemperature());

jeedom_post("1027", $viessmannApi->getSlope());

jeedom_post("1028", $viessmannApi->getShift());

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
jeedom_post("1018", $res);

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
jeedom_post("1019", $res);
jeedom_post("1021", (int)$viessmannApi->isHeatingBurnerActive());
jeedom_post("1023", $viessmannApi->getHotWaterStorageTemperature());
