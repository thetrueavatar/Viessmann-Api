<?php

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
    const HEATING_PROGRAM_ECO="heating.circuits.0.operating.programs.eco";
    const HEATING_PROGRAM_EXTERNAL="heating.circuits.0.operating.programs.external";
    const HEATING_PROGRAM_NORMAL="heating.circuits.0.operating.programs.normal";
    const HEATING_PROGRAM_REDUCED="heating.circuits.0.operating.programs.reduced";
    const HEATING_PROGRAM_STANDBY="heating.circuits.0.operating.programs.standby";
    const HEATING_PROGRAM_SUPPLY="heating.circuits.0.sensors.temperature.supply";
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
        $installationJson=$this->viessmanAuthClient->readData("general-management/installations");
        $installationEntity=Entity::fromArray(json_decode($installationJson,true));
        $modelInstallationEntity=$installationEntity->getEntities()[0];
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
        return $this->viessmanAuthClient->readData($this->featureHeatingUrl);
    }

    public function getOutsideTemperature():string{
        $outsideTempEntity=$this->getEntity(ViessmannAPI::HEATING_TEMP_OUTSIDE);
        return $outsideTempEntity->getProperty("value")["value"]."";
    }
    public function getBoilerTemperature():string{
        $boilerTempEntity=$this->getEntity(ViessmannAPI::BOILER_TEMP);
        return $boilerTempEntity->getProperty("value")["value"]."";
    }
    public function getSlope():string{
        $curveEntity=$this->getEntity(ViessmannAPI::HEATING_CURVE);
        return $curveEntity->getProperty("slope")["value"]."";
    }

    public function getShift():string{
        $curveEntity=$this->getEntity(ViessmannAPI::HEATING_CURVE);
        return $curveEntity->getProperty("shift")["value"]."";
    }
    public function setCurve($shift,$slope){
        $this->setRawJsonData(ViessmannAPI::HEATING_CURVE,"setCurve","{\"shift\":".$shift.",\"slope\":".$slope."}");
    }
    public function getActiveMode():string{
        $activeModeEntity=$this->getEntity(ViessmannAPI::HEATING_OPERATING_MODES);
        return $activeModeEntity->getProperty("value")["value"]."";

    }
    public function setActiveMode($mode){
        $this->setRawJsonData(ViessmannAPI::HEATING_OPERATING_MODES,"setMode","{\"mode\":".$mode."}");
    }
    public function getActiveProgram():string{
        $activeProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_ACTIVE);
        return $activeProgramEntity->getProperty("value")["value"]."";
    }

    public function isHeatingBurnerActive(): bool {
        $heatingBurnerEntity=$this->getEntity(ViessmannAPI::HEATING_BURNER);
        return $heatingBurnerEntity->getProperty("active")["value"];
    }

    public function isDhwModeActive():bool{
        $dhwModeActiveEntity=$this->getEntity(ViessmannAPI::HEATING_DWH_MODE);
        return $dhwModeActiveEntity->getProperty("active")["value"];
    }
    public function getComfortProgramTemperature():string{
        $comfortProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_COMFORT);
        return $comfortProgramEntity->getProperty("temperature")["value"]."";
    }
    public function setComfortProgramTemperature($temperature){
        $this->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_COMFORT,"setTemperature","{\"targetTemperature\":".$temperature."}");
    }
    public function getEcoProgramTemperature():string{
        $ecoProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_ECO);
        return $ecoProgramEntity->getProperty("temperature")["value"]."";
    }
    public function activateEcoProgram($temperature){
        $this->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_ECO,"activate","{\"temperature\":".$temperature."}");
    }
    public function deActivateEcoProgram(){
        $this->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_ECO,"deactivate",null);
    }
    public function getExternalProgramTemperature():string{
        $externalProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_EXTERNAL);
        return $externalProgramEntity->getProperty("temperature")["value"]."";
    }
    public function setExternalProgramTemperature($temperature){
        $this->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_REDUCED,"setTemperature","{\"targetTemperature\":".$temperature."}");
    }
    public function getNormalProgramTemperature():string{
        $normalProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_NORMAL);
        return $normalProgramEntity->getProperty("temperature")["value"]."";
    }
    public function setNormalProgramTemperature($temperature){
        $this->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_NORMAL,"setTemperature","{\"targetTemperature\":".$temperature."}");
    }
    public function getReducedProgramTemperature():string{
        $reducedProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_REDUCED);
        return $reducedProgramEntity->getProperty("temperature")["value"]."";
    }
    public function setReducedProgramTemperature($temperature){
        $this->setRawJsonData(ViessmannAPI::HEATING_PROGRAM_REDUCED,"setTemperature","{\"targetTemperature\":".$temperature."}");
    }
    public function isInStandbyMode():bool{
        $standbyProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_STANDBY);
        return $standbyProgramEntity->getProperty("active")["value"];
    }
    public function getSupplyProgramTemperature():string{
        $supplyProgramEntity=$this->getEntity(ViessmannAPI::HEATING_PROGRAM_SUPPLY);
        return $supplyProgramEntity->getProperty("value")["value"]."";
    }
    public function getRawJsonData($resources):string{
        try{
            return $this->viessmanAuthClient->readData($this->featureHeatingUrl."/".$resources);
        }catch (TokenResponseException $e){
            throw new \ViessmannApiException("Erreur lors de l'appel. Si 400 bad Request alors mauvais structure de données. Si 502, souvent une erreur de format donnée(20.0 au lieu de 20,...)",0,$e);
        }
    }
    private function getEntity($resources):Entity{
        return Entity::fromArray(json_decode($this->getRawJsonData($resources),true));
    }
    public function setRawJsonData($feature, $action, $data){
        $this->viessmanAuthClient->setData($this->featureHeatingUrl."/".$feature."/".$action,$data);
    }
    public function setDhwTemperature($temperature){
        $data="{\"temperature\": $temperature}";
        $this->viessmanAuthClient->setData($this->featureHeatingUrl."/".ViessmannAPI::HEATING_DWH_TEMPERATURE."/setTargetTemperature",$data);
    }


}
