<?php
include __DIR__.'/../index.php';
use Viessmann\API\ViessmannAPI;

$params = parseProperties(file_get_contents(__DIR__ . "/credentials.properties"));
//$params = [
//    "user" => trim("$params[0]"),
//    "pwd" => trim("$params[1]"),
//    "installationId" =>trim("$params[2]"),
//    "gatewayId" =>trim("$params[3]"),
//    "clientId" =>trim("$params[4]"),
//    "deviceId" => "0",
//    "circuitId" => "0"
//];
function print_exception($e){
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "Trace:" . $e->getTraceAsString() . "\n";
}
;
$errorHandler= function($e)
{
    $currentException=$e;
    do {
        print_exception($currentException);
    } while ($currentException = $currentException->getPrevious());
};

set_exception_handler($errorHandler);
try {
    $viessmannApi = new ViessmannAPI($params);
} catch (ViessmannApiException $e) {
    $errorHandler($e);
    exit();
}
function parseProperties($fileContent) {
    $result = [];
    $fileContent = str_replace("\r\n", "\n", $fileContent);
    $lines = explode("\n", $fileContent);
    $lastkey = '';
    $appendNextLine = false;
    foreach ($lines as $l) {
        $cleanLine = trim($l);
        if ($cleanLine === '') continue;
        if (strpos($cleanLine, '#') === 0) continue; // is comment ... move on

        $endsWithSlash = substr($l, -1) === '\\';
        if ($appendNextLine) {
            $result[$lastkey] .= "\n" . substr($l, 0, $endsWithSlash ? -1 : 10000);
            if (!$endsWithSlash) { // last line of multi-line property does not end with '\' char
                $appendNextLine = false;
            }
        } else {
            $key = trim(substr($l, 0, strpos($l, '=')));
            $value = substr($l,strpos($l,'=') + 1, $endsWithSlash ? -1 : 10000);
            $lastkey = $key;
            $result[$key] = $value;
            $appendNextLine = $endsWithSlash;
        }
    }
    return $result;
}