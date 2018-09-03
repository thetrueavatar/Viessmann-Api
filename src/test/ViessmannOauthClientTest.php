<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 31/08/18
 * Time: 12:43
 */
namespace Viessmann\API\Test;
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

use PHPUnit\Framework\TestCase;
use Viessmann\Oauth\ViessmannOauthClient;

class ViessmannOauthClientTest extends TestCase
{

    public function testAll()
    {
        $credentials = file("../../resources/credentials.properties");
        $params = [
            "user" => trim("$credentials[0]", "\n"),
            "pwd" => "$credentials[1]",
            "uri" => "vicare://oauth-callback/everest"
        ];

        $viessmannAuthClient = new ViessmannOauthClient($params);
        $code = $viessmannAuthClient->getCode();
        self::assertNotNull($viessmannAuthClient->getToken($code));
//        self::assertNotNull($viessmannAuthClient->request("general-management/installations?expanded=true&"));
        echo $viessmannAuthClient->request("https://api.viessmann-platform.io/operational-data/installations/55994/gateways/7571381753685105/devices/0/features/heating.dhw.sensors.temperature.hotWaterStorage");
    }
//https://api.viessmann-platform.io/operational-data/installations/55994/features/gateway.devices
    #                            /operational-data/installations/55994/gateways/7571381753685105/features/gateway.devices
}
