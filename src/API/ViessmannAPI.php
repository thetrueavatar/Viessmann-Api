<?php

namespace Viessmann\API;

use TomPHP\Siren\Entity;
use Viessmann\Oauth\ViessmannOauthClient;

final class ViessmannAPI
{
    const HEATING_BURNER = "heating.burner";
    const HEATING_CIRCUITS = "heating.circuits";
    const HEATING_CURVE = "heating.curve";
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
    const SUPPLY_PROGRAM = "sensors.temperature.supply";
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
        $this->circuitId = $param["circuitId"] ?? 0;
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
        return $this->installationId . "";
    }

    /**
     * @return string
     */
    public function getGatewayId(): string
    {
        return $this->gatewayId . "";
    }

    public function getFeatures(): String
    {
        return $this->viessmanAuthClient->readData($this->featureHeatingUrl);
    }

    public function getOutsideTemperature(): string
    {
        $outsideTempEntity = $this->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_OUTSIDE);
        return $outsideTempEntity->getProperty("value")["value"] . "";
    }

    public function getBoilerTemperature(): string
    {
        $boilerTempEntity = $this->getEntity(ViessmannFeature::HEATING_BOILER_SENSORS_TEMPERATURE_MAIN);
        return $boilerTempEntity->getProperty("value")["value"] . "";
    }

    public function getSlope($circuitId = NULL): string
    {
        $curveEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::HEATING_CURVE));
        return $curveEntity->getProperty("slope")["value"] . "";
    }

    public function getShift($circuitId = NULL): string
    {
        $curveEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::HEATING_CURVE));
        return $curveEntity->getProperty("shift")["value"] . "";
    }

    public function setCurve($shift, $slope, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::HEATING_CURVE), "setCurve", "{\"shift\":" . $shift . ",\"slope\":" . $slope . "}");
    }

    public function getActiveMode($circuitId = NULL): string
    {
        $activeModeEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::ACTIVE_OPERATING_MODE));
        return $activeModeEntity->getProperty("value")["value"] . "";

    }

    public function setActiveMode($mode, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::OPERATING_MODES), "setMode", "{\"mode\":\"" . $mode . "\"}");
    }

    public function getActiveProgram($circuitId = NULL): string
    {
        $activeProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::ACTIVE_PROGRAM));
        return $activeProgramEntity->getProperty("value")["value"] . "";
    }

    public function isHeatingBurnerActive(): bool
    {
        $heatingBurnerEntity = $this->getEntity(ViessmannFeature::HEATING_BURNER);
        return $heatingBurnerEntity->getProperty("active")["value"];
    }

    public function isDhwModeActive($circuitId = NULL): bool
    {
        $dhwModeActiveEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::DHW_MODE));
        return $dhwModeActiveEntity->getProperty("active")["value"];
    }

    public function getComfortProgramTemperature($circuitId = NULL): string
    {
        $comfortProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::COMFORT_PROGRAM));
        return $comfortProgramEntity->getProperty("temperature")["value"] . "";
    }

    public function setComfortProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::COMFORT_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function getEcoProgramTemperature($circuitId = NULL): string
    {
        $ecoProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::ECO_PROGRAM));
        return $ecoProgramEntity->getProperty("temperature")["value"] . "";
    }

    public function activateEcoProgram($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::ECO_PROGRAM), "activate", "{\"temperature\":" . $temperature . "}");
    }

    public function deActivateEcoProgram($circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::ECO_PROGRAM), "deactivate", null);
    }

    public function getExternalProgramTemperature($circuitId = NULL): string
    {
        $externalProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::EXTERNAL_PROGRAM));
        return $externalProgramEntity->getProperty("temperature")["value"] . "";
    }

    public function setExternalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData(self::HEATING_PROGRAM_REDUCED, "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function getNormalProgramTemperature($circuitId = NULL): string
    {
        $normalProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::NORMAL_PROGRAM));
        return $normalProgramEntity->getProperty("temperature")["value"] . "";
    }

    public function setNormalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::NORMAL_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function getReducedProgramTemperature($circuitId = NULL): string
    {
        $reducedProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::REDUCED_PROGRAM));
        return $reducedProgramEntity->getProperty("temperature")["value"] . "";
    }

    public function setReducedProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::REDUCED_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    public function isInStandbyMode($circuitId = NULL): bool
    {
        $standbyProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::STANDBY_PROGRAM));
        return $standbyProgramEntity->getProperty("active")["value"];
    }

    public function getSupplyProgramTemperature($circuitId = NULL): string
    {
        $supplyProgramEntity = $this->getEntity($this->buildFeature(self::HEATING_CIRCUITS, $circuitId, self::SUPPLY_PROGRAM));
        return $supplyProgramEntity->getProperty("value")["value"] . "";
    }

    public function getRawJsonData($resources): string
    {
        try {
            return $this->viessmanAuthClient->readData($this->featureHeatingUrl . "/" . $resources);
        } catch (TokenResponseException $e) {
            throw new \ViessmannApiException("Erreur lors de l'appel. Si 400 bad Request alors mauvais structure de données. Si 502, souvent une erreur de format donnée(20.0 au lieu de 20,...)", 0, $e);
        }
    }

    private function getEntity($resources): Entity
    {
        return Entity::fromArray(json_decode($this->getRawJsonData($resources), true));
    }

    public function setRawJsonData($feature, $action, $data)
    {
        $this->viessmanAuthClient->setData($this->featureHeatingUrl . "/" . $feature . "/" . $action, $data);
    }

    public function setDhwTemperature($temperature)
    {
        $data = "{\"temperature\": $temperature}";
        $this->viessmanAuthClient->setRawJsonData(ViessmannFeature::HEATING_DHW_TEMPERATURE, "setTargetTemperature", $data);
    }

    private function buildFeature($prefix, $circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return $prefix . "." . $circuitId . "." . $feature;
    }

}
