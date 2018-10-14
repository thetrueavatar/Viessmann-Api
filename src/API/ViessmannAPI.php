<?php

namespace Viessmann\API;

use OAuth\Common\Http\Exception\TokenResponseException;
use TomPHP\Siren\Entity;
use Viessmann\Oauth\ViessmannOauthClient;

final class ViessmannAPI
{
    const HEATING_BURNER = "heating.burner";
    const HEATING_CIRCUITS = "heating.circuits";
    const HEATING_CURVE = "heating.curve";
    const SENSORS_TEMPERATURE_ROOM = "sensors.temperature.room";
    const ACTIVE_OPERATING_MODE = "operating.modes.active";
    const OPERATING_MODES = "operating.modes.active";
    const DHW_MODE = "operating.modes.dhw";
    const DHW_AND_HEATING_MODE = "operating.modes.dhwAndHeating";
    const FORCED_NORMAL_MODE = "operating.modes.forcedNormal";
    const FORCED_REDUCTED_MODE = "operating.modes.forcedReduced";
    const STANDY_MODE = "operating.modes.standby";
    const ACTIVE_PROGRAM = "operating.programs.active";
    const COMFORT_PROGRAM = "operating.programs.comfort";
    const ECO_PROGRAM = "operating.programs.eco";
    const EXTERNAL_PROGRAM = "operating.programs.external";
    const NORMAL_PROGRAM = "operating.programs.normal";
    const REDUCED_PROGRAM = "operating.programs.reduced";
    const STANDBY_PROGRAM = "operating.programs.standby";
    const SENSORS_TEMPERATURE_SUPPLY = "sensors.temperature.supply";
    const CIRCULATION_SCHEDULE = "circulation.schedule";
    private $installationId;
    private $gatewayId;
    private $viessmanAuthClient;
    private $featureHeatingUrl;
    private $circuitId;

    /**
     * ViessmannAPI constructor.
     */
    public function __construct($params)
    {
        $this->circuitId = $params["circuitId"] ?? 0;
        $this->viessmanAuthClient = new ViessmannOauthClient($params);
        $code = $this->viessmanAuthClient->getCode();
        $this->viessmanAuthClient->getToken($code);
        $installationJson = $this->viessmanAuthClient->readData("general-management/installations");
        $installationEntity = Entity::fromArray(json_decode($installationJson, true));
        $modelInstallationEntity = $installationEntity->getEntities()[0];
        $this->installationId = $modelInstallationEntity->getProperty('id');
        $modelDevice = $modelInstallationEntity->getEntities()[0];
        $this->gatewayId = $modelDevice->getProperty('serial');
        $this->featureHeatingUrl = "operational-data/installations/" . $this->installationId . "/gateways/" . $this->gatewayId . "/devices/" . ($params["deviceId"] ?? 0) . "/features";
    }

    public function getInstallationId(): string
    {
        return $this->installationId;
    }

    /**
     * @return string
     */
    public function getGatewayId(): string
    {
        return $this->gatewayId;
    }

    public function getFeatures(): String
    {
        return $this->viessmanAuthClient->readData($this->featureHeatingUrl);
    }

    public function getOutsideTemperature(): string
    {
        return $this->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_OUTSIDE)->getProperty("value")["value"];
    }

    public function getBoilerTemperature(): string
    {
        return $this->getEntity(ViessmannFeature::HEATING_BOILER_SENSORS_TEMPERATURE_MAIN)->getProperty("value")["value"];
    }

    public function getRoomTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::SENSORS_TEMPERATURE_ROOM))->getProperty("value")["value"];
    }

    public function getSlope($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::HEATING_CURVE))->getProperty("slope")["value"];
    }

    public function getShift($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::HEATING_CURVE))->getProperty("shift")["value"];
    }

    public function setCurve($shift, $slope, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::HEATING_CURVE), "setCurve", "{\"shift\":" . $shift . ",\"slope\":" . $slope . "}");
    }

    public function getActiveMode($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::ACTIVE_OPERATING_MODE))->getProperty("value")["value"];
    }

    public function setActiveMode($mode, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::OPERATING_MODES), "setMode", "{\"mode\":\"" . $mode . "\"}");
    }

    public function getActiveProgram($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::ACTIVE_PROGRAM))->getProperty("value")["value"];
    }

    public function isHeatingBurnerActive(): bool
    {
        return $this->getEntity(ViessmannFeature::HEATING_BURNER)->getProperty("active")["value"];
    }

    public function isDhwModeActive($circuitId = NULL): bool
    {
        return $this->getEntity($this->buildFeature($circuitId, self::DHW_MODE))->getProperty("active")["value"];
    }

    public function getComfortProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::COMFORT_PROGRAM))->getProperty("temperature")["value"];
    }

    public function setComfortProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function getEcoProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::ECO_PROGRAM))->getProperty("temperature")["value"];
    }

    public function activateEcoProgram($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::ECO_PROGRAM), "activate", "{\"temperature\":" . $temperature . "}");
    }

    public function deActivateEcoProgram($circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::ECO_PROGRAM), "deactivate", null);
    }

    public function getExternalProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::EXTERNAL_PROGRAM))->getProperty("temperature")["value"];
    }

    public function setExternalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::REDUCED_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function getNormalProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::NORMAL_PROGRAM))->getProperty("temperature")["value"];
    }

    public function setNormalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::NORMAL_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function getReducedProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::REDUCED_PROGRAM))->getProperty("temperature")["value"];
    }

    public function setReducedProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::REDUCED_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function isInStandbyMode($circuitId = NULL): bool
    {
        return $this->getEntity($this->buildFeature($circuitId, self::STANDBY_PROGRAM))->getProperty("active")["value"];
    }

    public function getSupplyProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::SENSORS_TEMPERATURE_SUPPLY))->getProperty("value")["value"];
    }

    public function getHotWaterStorageTemperature($circuit = NULL): string
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE)->getProperty("value")["value"];
    }

    public function getRawJsonData($resources): string
    {
        try {
            return $this->viessmanAuthClient->readData($this->featureHeatingUrl . "/" . $resources);
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("Unable to get data for url " . $this->featureHeatingUrl . "/" . $resources . "\n Reason: " . $e->getMessage(), 1, $e);
        }
    }

    private function getEntity($resources): Entity
    {

        $data = json_decode($this->getRawJsonData($resources), true);
            if (isset($data["statusCode"])) {
                throw new ViessmannApiException("Unable to get data for feature " . $resources . " on url " . $this->featureHeatingUrl . "\nReason: " . $data["message"], 1);
            }

        return Entity::fromArray($data, true);

    }


    public function setDhwTemperature($temperature)
    {
        $data = "{\"temperature\": $temperature}";
        $this->setRawJsonData(ViessmannFeature::HEATING_DHW_TEMPERATURE, "setTargetTemperature", $data);
    }

    public function setRawJsonData($feature, $action, $data)
    {
        $this->viessmanAuthClient->setData($this->featureHeatingUrl . "/" . $feature . "/" . $action, $data);
    }

    private function buildFeature($circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return self::HEATING_CIRCUITS . "." . $circuitId . "." . $feature;
    }

}
