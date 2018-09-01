<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 31/08/18
 * Time: 12:43
 */
namespace Viessman\API\Test;
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

use PHPUnit\Framework\TestCase;
use Viessman\Oauth\ViessmanOauthClient;

class ViessmanOauthClientTest extends TestCase
{

    public function testAll()
    {
        $credentials = file("../../resources/credentials.properties");
        $params=[
            "user"=>trim("$credentials[0]","\n"),
            "pwd"=>"$credentials[1]",
            "uri"=>"vicare://oauth-callback/everest"
        ];

        $viessmanAuthClient=new ViessmanOauthClient($params);
        $code=$viessmanAuthClient->getCode();
        self::assertNotNull($viessmanAuthClient->getToken($code));
        self::assertNotNull($viessmanAuthClient->request("general-management/installations?expanded=true&"));
    }


}
