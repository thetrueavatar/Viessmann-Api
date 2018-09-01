<?php declare(strict_types=1);

namespace Viessmann\API;

use TomPHP\Siren\{ActionBuilder, Entity, Action};
use Viessmann\Oauth\ViessmannOauthClient;
final class ViessmannAPI
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
    const HEATING_CIRCUITS_1_DHW="heating.circuits.1.dhw";
    const HEATING_DWH_TEMPERATURE="heating.dhw.temperature";
    const HEATING_DWH_SENSORS="heating.dhw.sensors";
    const HEATING_DWH_SENSORS_TEMPERATURE="heating.dhw.sensors.temperature";
    const HEATING_DWH_SCHEDULE="heating.dhw.schedule";
    const HEATING_DWH_TEMPERATURE_HOTWATER_STORAGE="heating.dhw.sensors.temperature.hotWaterStorage";
    const HEATING_DWH_TEMPERATURE_OUTLET="heating.dhw.sensors.temperature.outlet";
    const HEATING_GAS_CONSUMPTION_DHW="heating.gas.consumption.dhw";
    const HEATING_TEMP_OUTSIDE="heating.sensors.temperature.outside";
    const HEATING_SENSORS_TEMPERATURE="heating.sensors.temperature";
    const HEATING_TIMEBASE="heating.service.timeBased";
    const HEATING_SENSORS="heating.sensors";
    const GATEWAY_DEVICES="gateway.devices";
    private $installationId;
    private $gatewayId;
    private $viessmanAuthClient;
    private $featureHeatingUrl;

    /**
     * ViessmannAPI constructor.
     */
    public function __construct($params)
    {
        $this->viessmanAuthClient=new ViessmannOauthClient($params);
        $code=$this->viessmanAuthClient->getCode();
        $this->viessmanAuthClient->getToken($code);
        $installationJson=$this->viessmanAuthClient->request("general-management/installations");
        $installationEntity=Entity::fromArray(json_decode($installationJson,true));
        $modelInstallationEntity=$installationEntity->getEntitiesByClass("model.installation")[0];
        $this->installationId=$modelInstallationEntity->getProperty('id');
        $modelDevice=$modelInstallationEntity->getEntities()[0];
        $this->gatewayId=$modelDevice->getProperty('serial');
        $this->featureHeatingUrl="operational-data/installations/".$this->installationId."/gateways/".$this->gatewayId."/devices/0/features";

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
        return $this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl));

    }
    public function getOtherFeatures():String{
        return $this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl));

    }

    public function getOutsideTemperature():string{
        $outsideTempEntity=Entity::fromArray(json_decode($this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl."/".ViessmannAPI::HEATING_TEMP_OUTSIDE)),true));
        return $outsideTempEntity->getProperty("value")["value"]."";
    }
    public function getBoilerTemperture():string{
        $boilerTempEntity=Entity::fromArray(json_decode($this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl."/".ViessmannAPI::BOILER_TEMP)),true));
        return $boilerTempEntity->getProperty("value")["value"]."";
    }
    public function getSlope():string{
        $curveEntity=Entity::fromArray(json_decode($this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl."/".ViessmannAPI::HEATING_CURVE)),true));
        return $curveEntity->getProperty("slope")["value"]."";
    }
    public function getShift():string{
        $curveEntity=Entity::fromArray(json_decode($this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl."/".ViessmannAPI::HEATING_CURVE)),true));
        return $curveEntity->getProperty("shift")["value"]."";
    }
    public function getActiveMode():string{
        $activeModeEntity=Entity::fromArray(json_decode( $this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl."/".ViessmannAPI::HEATING_OPERATING_MODES)),true));
        return $activeModeEntity->getProperty("value")["value"]."";

    }
    public function getActiveProgram():string{
        $activeProgramEntity=Entity::fromArray(json_decode( $this->formatData($this->viessmanAuthClient->request($this->featureHeatingUrl."/".ViessmannAPI::HEATING_PROGRAM_ACTIVE)),true));
        return $activeProgramEntity->getProperty("value")["value"]."";
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
