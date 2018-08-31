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
        //TODO provide credentials as construct param
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
    public function getFeatures():String{
        return $this->formatData($this->viessmanAuthClient->request("operational-data/installations/".$this->installationId."/gateways/".$this->gatewayId."/devices/0/features"));

    }

    private function formatData(string $request)
    {
        return $request;
        // TODO find a better way to display data
//        $featureEntity=Entity::fromArray(json_decode($this->viessmanAuthClient->request("operational-data/installations/".$this->installationId."/gateways/".$this->gatewayId."/devices/0/features"),true));
//        $data="{data=[";
//        $i=1;
//        $size=count($featureEntity->getEntities());
//        foreach ($featureEntity->getEntities() as $entity){
//               $data=$data."{";
//               $data=$data."classes :".(json_encode($entity->getClasses())).",";
//               $data=$data."properties :".json_encode($entity->getProperties());
//               $data=$data."}";
//               if ($i!=$size){
//                   $data=$data.",";
//               }
//        };
//        return $data."]}";
    }
}
