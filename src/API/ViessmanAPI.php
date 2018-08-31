<?php declare(strict_types=1);

namespace Viessman\API;

use TomPHP\Siren\{ActionBuilder, Entity, Action};
use Viessman\Oauth\ViessmanOauthClient;
final class ViessmanAPI
{

    private $installationId;
    /**
     * ViessmanAPI constructor.
     */
    public function __construct()
    {
        $credentials = file("../../resources/credentials.properties");
        $params=[
            "user"=>trim("$credentials[0]","\n"),
            "pwd"=>"$credentials[1]",
            "uri"=>"vicare://oauth-callback/everest"
        ];

        $viessmanAuthClient=new ViessmanOauthClient($params);
        $code=$viessmanAuthClient->getCode();
        $viessmanAuthClient->getToken($code);
        $installationJson=$viessmanAuthClient->request("general-management/installations?expanded=true&");
        $installation=Entity::fromArray(json_decode($installationJson,true));
        $this->installationId=$installation->getEntitiesByClass("model.installation")[0]->getProperty('id');
    }

    public function getOutsideTemperature():string{

    }
    public function getInstallationId():int{
        return $this->installationId;
    }
}
