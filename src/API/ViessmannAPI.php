<?php

namespace Viessmann\API;

use OAuth\Common\Http\Exception\TokenResponseException;
use TomPHP\Siren\Entity;
use Viessmann\Oauth\ViessmannOauthClientImpl;

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
    const DHW_SCHEDULE = "dhw.schedule";
    const HEATING_SCHEDULE = "heating.schedule";
    const CIRCULATION_PUMP = "circulation.pump";
    private $viessmanAuthClient;
    private $circuitId;

    /**
     * ViessmannAPI constructor.
     */
    public function __construct($params, $viessmannOauthClient = NULL)
    {
        $this->circuitId = $params["circuitId"] ?? 0;
        $this->viessmanAuthClient = $viessmannOauthClient ?? new ViessmannOauthClientImpl($params);
    }

    /**
     * @return String a json string that contains all the features at once in Siren Json style
     */
    public function getFeatures(): String
    {
        return $this->viessmanAuthClient->readData("");
    }

    /**
     * @return string the outside temperature if available
     * @throws ViessmannApiException
     */
    public function getOutsideTemperature(): string
    {
        return $this->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_OUTSIDE)->getProperty("value")["value"];
    }

    /**
     * @return string the current Boiler Temperature
     * @throws ViessmannApiException
     */
    public function getBoilerTemperature(): string
    {
        return $this->getEntity(ViessmannFeature::HEATING_BOILER_SENSORS_TEMPERATURE_MAIN)->getProperty("value")["value"];
    }

    /**
     * @param null $circuitId
     * @return string the Room temperature
     * @throws ViessmannApiException
     */
    public function getRoomTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::SENSORS_TEMPERATURE_ROOM))->getProperty("value")["value"];
    }

    /**
     * @param null $circuitId
     * @return string the slope configured
     * @throws ViessmannApiException
     */
    public function getSlope($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::HEATING_CURVE))->getProperty("slope")["value"];
    }

    /**
     * @param null $circuitId
     * @return string the shift configured
     * @throws ViessmannApiException
     */
    public function getShift($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::HEATING_CURVE))->getProperty("shift")["value"];
    }

    /**
     * @param $shift the new shift to set
     * @param $slope the new slope to set
     * @param null $circuitId
     */
    public function setCurve($shift, $slope, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::HEATING_CURVE), "setCurve", "{\"shift\":" . $shift . ",\"slope\":" . $slope . "}");
    }

    /**
     * @param null $circuitId
     * @return string the activeMode( "standby","dhw","dhwAndHeating","forcedReduced","forcedNormal")
     * @throws ViessmannApiException
     */
    public function getActiveMode($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::ACTIVE_OPERATING_MODE))->getProperty("value")["value"];
    }

    /**
     * Set the active mode to the given mode
     * @param $mode the activeMode( "standby","dhw","dhwAndHeating","forcedReduced","forcedNormal")
     * @param null $circuitId
     */
    public function setActiveMode($mode, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::OPERATING_MODES), "setMode", "{\"mode\":\"" . $mode . "\"}");
    }

    /**
     * @param null $circuitId
     * @return string the active program("comfort","eco","external","holiday","normal","reduced", "standby")
     * @throws ViessmannApiException
     */
    public function getActiveProgram($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::ACTIVE_PROGRAM))->getProperty("value")["value"];
    }

    /**
     * @return bool true if heating burner is active. False otherwise
     * @throws ViessmannApiException
     */
    public function isHeatingBurnerActive(): bool
    {
        return $this->getEntity(ViessmannFeature::HEATING_BURNER)->getProperty("active")["value"];
    }

    /**
     * @return bool true if DhwMode is active. False otherwise
     * @throws ViessmannApiException
     */
    public function isDhwModeActive($circuitId = NULL): bool
    {
        return $this->getEntity($this->buildFeature($circuitId, self::DHW_MODE))->getProperty("active")["value"];
    }

    /**
     * @param null $circuitId
     * @return string Conformat program temperature insntruction
     * @throws ViessmannApiException
     */
    public function getComfortProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::COMFORT_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param $temperature onformat program temperature insntruction
     * @param null $circuitId
     */
    public function setComfortProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return string Eco program temperature insntruction
     * @throws ViessmannApiException
     */
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

    /**
     * @param null $circuitId
     * @return string External program temperature insntruction
     * @throws ViessmannApiException
     */
    public function getExternalProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::EXTERNAL_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param null $circuitId
     * @return string External program temperature insntruction
     * @throws ViessmannApiException
     */
    public function setExternalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::REDUCED_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return string Normal program temperature insntruction
     * @throws ViessmannApiException
     */
    public function getNormalProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::NORMAL_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param null $circuitId
     * @return string Normal program temperature insntruction
     * @throws ViessmannApiException
     */
    public function setNormalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::NORMAL_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return string Reduced program temperature insntruction
     * @throws ViessmannApiException
     */
    public function getReducedProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::REDUCED_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param null $circuitId
     * @return string Reduced program temperature insntruction
     * @throws ViessmannApiException
     */
    public function setReducedProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::REDUCED_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return bool true if is standy. False otherwise
     * @throws ViessmannApiException
     */
    public function isInStandbyMode($circuitId = NULL): bool
    {
        return $this->getEntity($this->buildFeature($circuitId, self::STANDBY_PROGRAM))->getProperty("active")["value"];
    }

    public function getSupplyProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::SENSORS_TEMPERATURE_SUPPLY))->getProperty("value")["value"];
    }

    /**
     * @param null $circuit
     * @return string Hot Water storage temperature
     * @throws ViessmannApiException
     */
    public function getHotWaterStorageTemperature($circuit = NULL): string
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE)->getProperty("value")["value"];
    }

    /**
     * Return the Gas consumption for DHW. A period is needeed amongs day(default),week,month,year. Currently the number are not the same that displayed on heating device
     * @param string $period amongst enume "day","week","month","year
     * @return if day an array containing daily consommation for the last 7 days(each entry is consumption for a day)
     *         if week an array containing weekly consommation for the last 52 weeks(each entry is consumption for a week)
     *         if month an array containing monthly consommation for the last 12 month(each entry is consumption for one month)
     *         if year an array containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public function getDhwGasConsumption($period = "day")
    {
        return $this->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_DHW)->getProperty($period)["value"];
    }

    /**
     * Return the Gas consumption for Heating. A period is needeed amongs day(default),week,month,year. Currently the number are not the same that displayed on heating device
     * @param string $period amongst enume "day","week","month","year
     * @return if day an array containing daily consommation for the last 7 days(each entry is consumption for a day)
     *         if week an array containing weekly consommation for the last 52 weeks(each entry is consumption for a week)
     *         if month an array containing monthly consommation for the last 12 month(each entry is consumption for one month)
     *         if year an array containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public function getHeatingGasConsumption($period = "day")
    {
        return $this->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_HEATING)->getProperty($period)["value"];
    }

    /**
     * @param string $type the type of statistics("hours":number of active hours or "starts": number of start)
     * @return mixed number of hours or number of starts
     * @throws ViessmannApiException
     */
    public function getHeatingBurnerStatistics($type = "hours")
    {
        return $this->getEntity(ViessmannFeature::HEATING_BURNER_STATISTICS)->getProperty($type)["value"];
    }

    /**
     * @param null $circuitId
     * @return map containing the Dhw schedule for each days in format:
     * "mon": [
     * {
     * "start": "03:00",
     * "end": "24:00",
     * "mode": "on",
     * "position": 1
     * }
     * ]
     * @throws ViessmannApiException
     */
    public function getDhwSchedule($circuitId = NULL)
    {
        return $this->getEntity($this->buildFeature($circuitId, self::DHW_SCHEDULE))->getProperties();
    }

    /**
     * @param null $circuitId
     * @return map containing the Circulation schedule for each days in format:
     * "mon": [
    {
    "start": "03:00",
    "end": "24:00",
    "mode": "on",
    "position": 1
    }
    ]
     * @throws ViessmannApiException
     */
    public function getCirculationSchedule($circuitId = NULL)
    {
        return $this->getEntity($this->buildFeature($circuitId, self::CIRCULATION_SCHEDULE))->getProperties();
    }

    /**
     * @param null $circuitId
     * @return map containing the Heating schedule for each days in format:
     * "mon": [
    {
    "start": "03:00",
    "end": "24:00",
    "mode": "on",
    "position": 1
    }
    ]
     * @throws ViessmannApiException
     */
    public function getHeatingSchedule($circuitId = NULL)
    {
        return $this->getEntity($this->buildFeature($circuitId, self::HEATING_SCHEDULE))->getProperties();
    }

    public function getHeatingBurnerCurrentPower()
    {
        return $this->getEntity(ViessmannFeature::HEATING_BURNER_CURRENT_POWER)->getProperty("value")["value"];
    }

    public function getHeatingBurnerModulation()
    {
        return $this->getEntity(ViessmannFeature::HEATING_BURNER_MODULATION)->getProperty("value")["value"];
    }

    public function getCirculationPumpStatus($circuitId = NULL)
    {
        return $this->getEntity($this->buildFeature($circuitId, self::CIRCULATION_PUMP))->getProperty("status")["value"];
    }

    public function isDhwCharging(): bool
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_CHARGING)->getProperty("active")["value"];
    }

    public function getDhwChargingLevel(): String
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_CHARGING_LEVEL)->getProperty("value")["value"];
    }

    public function isOneTimeDhwCharge(): String
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_ONETIMECHARGE)->getProperty("active")["value"];
    }

    public function getDhwPumpsCirculation(): String
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_PUMPS_CIRCULATION)->getProperty("status")["value"];
    }

    public function getDhwPumpsPrimary(): String
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_PUMPS_PRIMARY)->getProperty("status")["value"];
    }

    public function getDhwTemperatureOutlet(): String
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_OUTLET)->getProperty("value")["value"];
    }

    public function getDhwTemperature(): String
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_TEMPERATURE)->getProperty("value")["value"];
    }

    public function setDhwTemperature($temperature)
    {
        $data = "{\"temperature\": $temperature}";
        $this->setRawJsonData(ViessmannFeature::HEATING_DHW_TEMPERATURE, "setTargetTemperature", $data);
    }

    public function setRawJsonData($feature, $action, $data)
    {
        $this->viessmanAuthClient->setData($feature, $action, $data);
    }

    public function getRawJsonData($resources): string
    {
        try {
            return $this->viessmanAuthClient->readData($resources);
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("Unable to get data for feature" . $resources . "\n Reason: " . $e->getMessage(), 1, $e);
        }
    }

    private function getEntity($resources): Entity
    {

        $data = json_decode($this->getRawJsonData($resources), true);
        if (isset($data["statusCode"])) {
            throw new ViessmannApiException("Unable to get data for feature " . $resources . "\nReason: " . $data["message"], 1);
        }

        return Entity::fromArray($data, true);

    }


    private function buildFeature($circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return self::HEATING_CIRCUITS . "." . $circuitId . "." . $feature;
    }

}
