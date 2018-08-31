<?php declare(strict_types=1);

namespace Viessman\API;

use TomPHP\Siren\{ActionBuilder, Entity, Action};
use Viessman\Oauth\ViessmanOauthClient;
final class ViessmanAPI
{

    private $installationId;
    private $gatewayId;
    private $viessmanAuthClient;
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
        https://api.viessmann-platform.io/operational-data/installations/ /gateways/ /devices/0/features
        $this->viessmanAuthClient=new ViessmanOauthClient($params);
        $code=$this->viessmanAuthClient->getCode();
        $this->viessmanAuthClient->getToken($code);
        $installationJson=$this->viessmanAuthClient->request("general-management/installations");
        $installationEntity=Entity::fromArray(json_decode($installationJson,true));
        $modelInstallationEntity=$installationEntity->getEntitiesByClass("model.installation")[0];
        $this->installationId=$modelInstallationEntity->getProperty('id');
        $modelDevice=$modelInstallationEntity->getEntities()[0];
        $this->gatewayId=$modelDevice->getProperty('serial');
    }

    public function getOutsideTemperature():string{

    }
    public function getInstallationId():string{
        return $this->installationId."";
    }

    /**
     * @return string
     */
    public function getGatewayId(): string
    {
        return $this->gatewayId."";
    }
    public function getAllData():String{
        $featureEntity=Entity::fromArray(json_decode($this->viessmanAuthClient->request("operational-data/installations/".$this->installationId."/gateways/".$this->gatewayId."/devices/0/features"),true));
        foreach ($featureEntity->getEntities() as $entity){
               print_r($entity->getClasses());
               print_r($entity->getProperties());
        };
    }
}
