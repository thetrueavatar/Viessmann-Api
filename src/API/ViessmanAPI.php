<?php declare(strict_types=1);

namespace Viessman\API;

use TomPHP\Siren\{ActionBuilder, Entity, Action};
use Viessman\Oauth\ViessmanOauthClient;
final class ViessmanAPI
{
const BOILER_TEMP="heating.boiler.sensors.temperature.main";
const HEATING_BURNER="heating.burner";
const HEATING_CIRCUITS="heating.circuits";
const HEATING_CIRCUITS_0="heating.circuits.0";
const HEATING_CURVE="heating.circuits.0.heating.curve";
const HEATING_OPERATING_MODES="heating.circuits.0.operating.modes.active";
const HEATING_DWH_MODE="heating.circuits.0.operating.modes.dhw";
const HEATING_DWH_AND_HEATING_MODE="heating.circuits.0.operating.modes.dhwAndHeating";
const HEATING_FORCED_NORMAL_MODE="heating.circuits.0.operating.modes.forcedNormal";
const HEATING_FORCED_REDUCTED_MODE="heating.circuits.0.operating.modes.forcedReduced";
const HEATING_STANDY_MODE="heating.circuits.0.operating.modes.standby";
const HEATING_PROGRAM_ACTIVE="heating.circuits.0.operating.programs.active";
const HEATING_PROGRAM_COMFORT="heating.circuits.0.operating.programs.comfort";
const HEATING_PROGRAM_ECHO="heating.circuits.0.operating.programs.eco";
const HEATING_PROGRAM_EXTERNAL="heating.circuits.0.operating.programs.external";
const HEATING_PROGRAM_NORMAL="heating.circuits.0.operating.programs.normal";
const HEATING_PROGRAM_REDUCED="heating.circuits.0.operating.programs.reduced";
const HEATING_PROGRAM_STANDBY="heating.circuits.0.operating.programs.standby";
const HEATING_PROGRAM_SUPLY="heating.circuits.0.sensors.temperature.supply";
const HEATING_TIME_OFFSET="heating.device.time.offset";
const HEATING_DWH="heating.dhw";
const HEATING_DWH_SCHEDULE="heating.dhw.schedule";
const HEATING_TEMP_OUTSIDE="heating.sensors.temperature.outside";
const HEATING_TIMEBASE="heating.service.timeBased";
    private $installationId;
    private $gatewayId;
    private $viessmanAuthClient;
    private $featureUrl;
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
        $this->featureUrl="operational-data/installations/".$this->installationId."/gateways/".$this->gatewayId."/devices/0/features";
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
        return $this->formatData($this->viessmanAuthClient->request($this->featureUrl));

    }
    public function getOutsideTemperature():string{
        $outsideTempEntity=Entity::fromArray(json_decode($this->formatData($this->viessmanAuthClient->request($this->featureUrl."/".ViessmanAPI::HEATING_TEMP_OUTSIDE)),true));
        return $outsideTempEntity->getProperty("value")["value"]."";
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
