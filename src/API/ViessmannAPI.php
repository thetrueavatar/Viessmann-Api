<?php

namespace Viessmann\API;

use DateTime;
use TomPHP\Siren\Entity;
use Viessmann\API\proxy\impl\ViessmannFeatureLocalProxy;
use Viessmann\API\proxy\impl\ViessmannFeatureRemoteProxy;
use Viessmann\Oauth\ViessmannOauthClientImpl;

final class ViessmannAPI
{
    const HEATING_BURNER = "heating.burner";
    const HEATING_CIRCUITS = "heating.circuits";
    const HEATING_COMPRESSORS = "heating.compressors";
    const HEATING_CURVE = "heating.curve";
    const HEATING_FROSTPROTECTION = "frostprotection";
    const HEATING_COMPRESSOR_STATISTICS = "statistics";
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
    const HOLIDAY_PROGRAM = "operating.programs.holiday";
    const FIXED_PROGRAM = "operating.programs.fixed";
    const SENSORS_TEMPERATURE_SUPPLY = "sensors.temperature.supply";
    const CIRCULATION_SCHEDULE = "circulation.schedule";
    const DHW_SCHEDULE = "heating.dhw.schedule";
    const HEATING_SCHEDULE = "heating.schedule";
    const CIRCULATION_PUMP = "circulation.pump";
    private $circuitId;
    private $viessmannFeatureProxy;
    const STATISTICS = "statistics";
    private $viessmannOauthClient;
    const OPERATIONAL_DATA_INSTALLATIONS = "operational-data/installations/";
    private $installationId;
    private $gatewayId;

    /**
     * ViessmannAPI constructor.
     */
    public function __construct($params, $useCache = true, $viessmannRemoteFeatureProxy = NULL, $viessmannOauthClient = NULL)
    {
        $this->circuitId = $params["circuitId"] ?? 0;
        $this->viessmannOauthClient = $viessmannOauthClient ?? new ViessmannOauthClientImpl($params["user"], $params["pwd"]);
        if (!empty($params["installationId"]) && !empty($params["gatewayId"])) {
            $this->installationId = $params["installationId"];
            $this->gatewayId = $params["gatewayId"];
        } else {
            $installationEntity = $this->getInstallationEntity();
            $modelInstallationEntity = $installationEntity->getEntities()[0];
            $this->installationId = $modelInstallationEntity->getProperty('id');
            $modelDevice = $modelInstallationEntity->getEntities()[0];
            $this->gatewayId = $modelDevice->getProperty('serial');

        }
        $this->viessmannFeatureProxy = $viessmannRemoteFeatureProxy ?? new ViessmannFeatureRemoteProxy($this->viessmannOauthClient, $this->installationId, $this->gatewayId);

        if ($useCache) {
            $features = $this->viessmannFeatureProxy->getEntity("");
            $this->viessmannFeatureProxy = new ViessmannFeatureLocalProxy($features, $this->viessmannOauthClient, $this->installationId, $this->gatewayId);
        }
    }

    /**
     * @return string
     */
    public function getInstallationEntity()
    {
        try {
            $response = json_decode($this->viessmannOauthClient->readData("general-management/installations"), true);
            if (isset($response["statusCode"])) {
                if ($response["statusCode"] == "429") {
                    $epochtime = (int)($response["extendedPayload"]["limitReset"] / 1000);
                    $dt = new DateTime("@$epochtime");
                    $resetDate = $dt->format(DateTime::RSS);
                    throw new ViessmannApiException("\n\t Unable to read installation basic information \n\t Reason: " . $response["message"] . " Limit will be reset on " . $resetDate, 2);
                } else {
                    throw new ViessmannApiException("\n\t Unable to read installation basic information \n\t Reason: " . $response["message"], 2);

                }
            }
            return Entity::fromArray($response);
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);
        }
    }

    /**
     * @return string
     */
    public function getGatewayWifi()
    {
        try {
            return $this->viessmannOauthClient->readData("operational-data/installations/".$this->getInstallationId()."/gateways/".$this->getGatewayId()."/features/gateway.wifi" );
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }
    public function getGatewayFirmware()
    {
        try {
            return $this->viessmannOauthClient->readData("operational-data/installations/".$this->getInstallationId()."/gateways/".$this->getGatewayId()."/features/gateway.firmware" );
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }
    public function getGatewayStatus()
    {
        try {
            return $this->viessmannOauthClient->readData("operational-data/installations/".$this->getInstallationId()."/gateways/".$this->getGatewayId()."/features/gateway.status" );
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }
    public function getGatewayBmuconnection()
    {
        try {
            return $this->viessmannOauthClient->readData("operational-data/installations/".$this->getInstallationId()."/gateways/".$this->getGatewayId()."/features/gateway.bmuconnection" );
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }
    public function getGatewayDevices()
    {
        try {
            return $this->viessmannOauthClient->readData("operational-data/installations/".$this->getInstallationId()."/gateways/".$this->getGatewayId()."/features/gateway.devices" );
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }


    public
    function setRawJsonData($feature, $action, $data)
    {
        $this->viessmannFeatureProxy->setData($feature, $action, $data);

    }

    /**
     * @return mixed
     */
    public
    function getInstallationId()
    {
        return $this->installationId;
    }

    /**
     * @return mixed
     */
    public
    function getGatewayId()
    {
        return $this->gatewayId;
    }

    public
    function getRawJsonData($resources): string
    {
        return $this->viessmannFeatureProxy->getRawJsonData($resources);
    }

    /**
     * @return String containing a list of all the features having either a property either an action on it
     */
    public
    function getAvailableFeatures(): String
    {
        $features= json_decode($this->viessmannFeatureProxy->getRawJsonData(""),true);
        return implode(",\n", $features);
    }


    /**
     * @return string the outside temperature if available
     * @throws ViessmannApiException
     */
    public
    function getOutsideTemperature(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_OUTSIDE)->getProperty("value")["value"];
    }

    /**
     * @return string the current Boiler Temperature
     * @throws ViessmannApiException
     */
    public
    function getBoilerTemperature(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_BOILER_SENSORS_TEMPERATURE_MAIN)->getProperty("value")["value"];
    }

    /**
     * @param null $circuitId
     * @return string the Room temperature
     * @throws ViessmannApiException
     */
    public
    function getRoomTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::SENSORS_TEMPERATURE_ROOM))->getProperty("value")["value"];
    }

    /**
     * @param null $circuitId
     * @return string the slope configured
     * @throws ViessmannApiException
     */
    public
    function getSlope($circuitId = NULL, $features = NULL): string
    {
        if ($features) {
            return $features[$this->buildFeature($circuitId, self::HEATING_CURVE)]->getProperty("slope")["value"];
        } else {
            return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::HEATING_CURVE))->getProperty("slope")["value"];
        }
    }

    /**
     * @param null $circuitId
     * @return string the shift configured
     * @throws ViessmannApiException
     */
    public
    function getShift($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::HEATING_CURVE))->getProperty("shift")["value"];
    }

    /**
     * @param $shift the new shift to set
     * @param $slope the new slope to set
     * @param null $circuitId
     */
    public
    function setCurve($shift, $slope, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::HEATING_CURVE), "setCurve", "{\"shift\":" . $shift . ",\"slope\":" . $slope . "}");
    }

    /**
     * @param null $circuitId
     * @return string the frostprotection configured
     * @throws ViessmannApiException
     */
    public
    function getFrostprotection($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::HEATING_FROSTPROTECTION))->getProperty("status")["value"];
    }

    /**
     * @param null $circuitId
     * @return int the statistics for starts compressor
     * @throws ViessmannApiException
     */
    public
    function getHeatingCompressorStarts($circuitId = NULL): int
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeatureCompressors($circuitId, self::HEATING_COMPRESSOR_STATISTICS))->getProperty("starts")["value"];
    }

    /**
     * @param null $circuitId
     * @return double the statistics for hours run compressor
     * @throws ViessmannApiException
     */
    public
    function getHeatingCompressorHours($circuitId = NULL): float
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeatureCompressors($circuitId, self::HEATING_COMPRESSOR_STATISTICS))->getProperty("hours")["value"];
    }

    /**
     * @param null $circuitId
     * @param null $classNumber (possible 1-5)
     * @return int the statistics for load classes 1-5
     * @throws ViessmannApiException
     */
    public
    function getHeatingCompressorLoadClassHours($circuitId = NULL, $classNumber = NULL): int
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeatureCompressors($circuitId, self::HEATING_COMPRESSOR_STATISTICS))->getProperty($this->buildHeatingCompressorLoadCassParameter($classNumber))["value"];
    }

    /**
     * @return float the result for primary circuit sensor Temperature supply
     * @throws ViessmannApiException
     */
    public
    function getHeatingPrimaryCircuitTemperatureSupply(): float
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_PRIMARYCIRCUIT_SENSORS_TEMPERATURE_SUPPLY)->getProperty("value")["value"];
    }

    /**
     * @return float the result for secondary circuit sensor Temperature supply
     * @throws ViessmannApiException
     */
    public
    function getHeatingSecondaryCircuitTemperatureSupply(): float
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SECONDARYCIRCUIT_SENSORS_TEMPERATURE_SUPPLY)->getProperty("value")["value"];
    }

    /**
     * @return float the result for secondary circuit sensor Temperature return
     * @throws ViessmannApiException
     */
    public
    function getHeatingSecondaryCircuitTemperatureReturn(): float
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SECONDARYCIRCUIT_SENSORS_TEMPERATURE_RETURN)->getProperty("value")["value"];
    }

    /**
     * @param null $circuitId
     * @return string the activeMode( "standby","dhw","dhwAndHeating","forcedReduced","forcedNormal")
     * @throws ViessmannApiException
     */
    public
    function getActiveMode($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::ACTIVE_OPERATING_MODE))->getProperty("value")["value"];
    }

    /**
     * Set the active mode to the given mode
     * @param $mode the activeMode( "standby","dhw","dhwAndHeating","forcedReduced","forcedNormal")
     * @param null $circuitId
     */
    public
    function setActiveMode($mode, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::OPERATING_MODES), "setMode", "{\"mode\":\"" . $mode . "\"}");
    }

    /**
     * @param null $circuitId
     * @return string the active program("comfort","eco","external","holiday","normal","reduced", "standby")
     * @throws ViessmannApiException
     */
    public
    function getActiveProgram($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::ACTIVE_PROGRAM))->getProperty("value")["value"];
    }


    /**
     * @return bool true if heating burner is active. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isHeatingBurnerActive(): bool
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_BURNER)->getProperty("active")["value"];
    }

    /**
     * @return bool true if heating compressor is active. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isHeatingCompressorsActive($circuitId = NULL): bool
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_COMPRESSORS . "." . $circuitId)->getProperty("active")["value"];
    }

    /**
     * @return string statistics of the compressors
     */
    public
    function getHeatingCompressorsStatistics(): string
    {
        return json_encode($this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_COMPRESSORS . "." . $this->circuitId . "." . self::STATISTICS . "")->getProperties());
    }

    /**
     * @return bool true if DhwMode is active. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isDhwModeActive($circuitId = NULL): bool
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::DHW_MODE))->getProperty("active")["value"];
    }

    /**
     * @param null $circuitId
     * @return string Comfort program temperature programmed
     * @throws ViessmannApiException
     */
    public
    function getComfortProgramTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::COMFORT_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param $temperature Comfort program temperature to program
     * @param null $circuitId
     */
    public
    function setComfortProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return string Eco program temperature insntruction
     * @throws ViessmannApiException
     */
    public
    function getEcoProgramTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::ECO_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * Activate eco program
     * @param null $temperature optional temperature to set for eco program
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function activateEcoProgram($temperature = NULL, $circuitId = NULL)
    {
        $data = NULL;
        if (isset($temperature)) {
            $data = "{\"temperature\":" . $temperature . "}";
        } else {
            $data = "{}";
        }
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::ECO_PROGRAM), "activate", $data);
    }

    /**DeActivate eco program
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function deActivateEcoProgram($circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::ECO_PROGRAM), "deactivate", "{}");
    }

    /**
     * get scheduled holiday program
     * json object contains a property start and end. date are in format yyyy-MM-dd
     * @param null $circuitId
     * @return a json object containing a property start and a property end
     * @throws ViessmannApiException
     */
    public
    function getScheduledHolidayProgram($circuitId = NULL): string
    {
        $data = $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::HOLIDAY_PROGRAM));
        $schedule['start'] = $data->getProperty("start")["value"];
        $schedule['end'] = $data->getProperty("end")["value"];
        return json_encode($schedule);

    }

    /**
     * schedule holiday program
     * start en end are in xml datetime format. See https://www.w3schools.com/xml/schema_dtypes_date.asp form more details
     * @param $start of holiday in xml datetime format but seems to effectively only store date part(yyyy-MM-dd)
     * @param $end of holiday in datetime xml format
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function scheduleHolidayProgram($start, $end, $circuitId = NULL)
    {
        $data = "{\"start\":\"" . $start . "\", \"end\":\"" . $end . "\"}";
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::HOLIDAY_PROGRAM), "schedule", $data);
    }

    /** remove current holiday program's schedule
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function unscheduleHolidayProgram($circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::HOLIDAY_PROGRAM), "unschedule", "{}");
    }

    /**
     * Activate Comfort program
     * @param null $temperature
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function activateComfortProgram($temperature = NULL, $circuitId = NULL)
    {
        $data = NULL;
        if (isset($temperature)) {
            $data = "{\"temperature\":" . $temperature . "}";
        } else {
            $data = "{}";
        }
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "activate", $data);
    }

    /**
     * Deactivate Comfort Program
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function deActivateComfortProgram($circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "deactivate", "{}");
    }

    /**
     * @param null $circuitId
     * @return string External program temperature programmed
     * @throws ViessmannApiException
     */
    public
    function getExternalProgramTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::EXTERNAL_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param null $circuitId
     * @return string External program temperature to program
     * @throws ViessmannApiException
     */
    public
    function setExternalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::EXTERNAL_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return string Normal program temperature insntruction
     * @throws ViessmannApiException
     */
    public
    function getNormalProgramTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::NORMAL_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param null $circuitId
     * @return string Normal program temperature insntruction
     * @throws ViessmannApiException
     */
    public
    function setNormalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::NORMAL_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return string Reduced program temperature insntruction
     * @throws ViessmannApiException
     */
    public
    function getReducedProgramTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::REDUCED_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param null $circuitId
     * @return string Reduced program temperature insntruction
     * @throws ViessmannApiException
     */
    public
    function setReducedProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::REDUCED_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * @param null $circuitId
     * @return bool true if is standy. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isInStandbyMode($circuitId = NULL): bool
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::STANDBY_PROGRAM))->getProperty("active")["value"];
    }

    /**
     * @param null $circuitId
     * @return bool true if is Fixed. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isInFixedPrograms($circuitId = NULL): bool
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::FIXED_PROGRAM))->getProperty("active")["value"];
    }

    public
    function getSupplyTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::SENSORS_TEMPERATURE_SUPPLY))->getProperty("value")["value"];
    }


    /**
     * @param null $circuitId
     * @return string Hot Water storage temperature
     * @throws ViessmannApiException
     */
    public
    function getHotWaterStorageTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE)->getProperty("value")["value"];
    }


    /**
     * Return the Heating Solar Power production. A period is needed amongs day(default),week,month,year.
     * currently only day is returned
     * Currently the number
     * are not the same that displayed on heating device
     * @param string $period amongst enume "day","week","month","year
     * @return if day an array containing daily consommation for the last 7 days(each entry is consumption for a day)
     *         if week an array containing weekly consommation for the last 52 weeks(each entry is consumption for a week)
     *         if month an array containing monthly consommation for the last 12 month(each entry is consumption for one month)
     *         if year an array containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarPowerProduction($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_POWER_PRODUCTION)->getProperty($period)["value"];
    }


    /**
     * @return string heating solar sensors temperature collector
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarSensorsTemperatureCollector(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_SENSORS_TEMPERATURE_COLLECTOR)->getProperty("value")["value"];
    }


    /**
     * @return string heating solar power cumulative produced in kWh
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarPowerCumulativeProduced(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_POWER_CUMULATIVEPRODUCED)->getProperty("value")["value"];
    }


    /**
     * @return string heating solar sensors temperature dhw
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarSensorsTemperatureDhw(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_SENSORS_TEMPERATURE_DHW)->getProperty("value")["value"];
    }


    /**
     * @return string heating solar system operational hours
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarStatistics(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_STATISTICS)->getProperty("hours")["value"];
    }


    /**
     * @return string off/on for recharge suppression
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarRechargesuppression(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_RECHARGESUPPRESSION)->getProperty("status")["value"];
    }


    /**
     * @return string off/on for solar pump circuit
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarPumpsCircuit(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_PUMPS_CIRCUIT)->getProperty("status")["value"];
    }


    /**
     * Return the Heating consumption. A period is needeed amongs day(default),week,month,year. Currently the number
     * are not the same that displayed on heating device
     * @param string $period amongst enume "day","week","month","year
     * @return if day an array containing daily consommation for the last 7 days(each entry is consumption for a day)
     *         if week an array containing weekly consommation for the last 52 weeks(each entry is consumption for a week)
     *         if month an array containing monthly consommation for the last 12 month(each entry is consumption for one month)
     *         if year an array containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingPowerConsumption($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_POWER_CONSUMPTION)->getProperty($period)["value"];
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
    public
    function getDhwGasConsumption($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_DHW)->getProperty($period)["value"];
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
    public
    function getHeatingGasConsumption($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_HEATING)->getProperty($period)["value"];
    }

    /**
     * @param string $type the type of statistics("hours":number of active hours or "starts": number of start)
     * @return mixed number of hours or number of starts
     * @throws ViessmannApiException
     */
    public
    function getHeatingBurnerStatistics($type = "hours")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_BURNER_STATISTICS)->getProperty($type)["value"];
    }

    /**
     * @param null $circuitId
     * @return json containing the Dhw schedule for each days in format:
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
    public
    function getDhwSchedule(): string
    {
        return json_encode($this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_SCHEDULE)->getProperties());
    }

    /**
     * Replace the full schedule for DHW. Sample of schedule:
     * "{"\"mon\": [
     * {
     * \"start\": \"03:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 1
     * }
     * ],
     * \"tue\": [
     * {
     * \"start\": \"03:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 3
     * }
     * ],
     * \"wed\": [
     * {
     * \"start\": \"02:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 2
     * }
     * ],
     * \"thu\": [
     * {
     * \"start\": \"03:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * }
     * ],
     * \"fri\": [
     * {
     * \"start\": \"03:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 4
     * }
     * ],
     * \"sat\": [
     * {
     * \"start\": \"03:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * }
     * ],
     * \"sun\": [
     * {
     * \"start\": \"03:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * }
     * ]
     * }"
     * @param null $circuitId
     * @param $schedule
     * @return array
     * @throws ViessmannApiException
     */
    public
    function setRawDhwSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        $this->viessmannFeatureProxy->setData(self::DHW_SCHEDULE, "setSchedule", $data);
    }

    /**
     * @param null $circuitId
     * @return json containing the Circulation schedule for each days in format:
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
    public
    function getCirculationSchedule($circuitId = NULL): string
    {
        return json_encode($this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::CIRCULATION_SCHEDULE))->getProperties());
    }

    /**
     * Post a complete new schedule. Warning !!! this would erase all previous schedule. Sample:
     * "{
     * \"mon\": [
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 1
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 2
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 3
     * }
     * ],
     * \"tue\": [
     * {
     * \"start\": \"00:00\",
     * \"end\": \"23:50\",
     * \"mode\": \"on\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"00:10\",
     * \"mode\": \"on\",
     * \"position\": 1
     * },
     * {
     * \"start\": \"23:20\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 2
     * },
     * {
     * \"start\": \"05:30\",
     * \"end\": \"22:00\",
     * \"mode\": \"on\",
     * \"position\": 3
     * }
     * ],
     * \"wed\": [
     * {
     * \"start\": \"05:30\",
     * \"end\": \"22:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * }
     * ],
     * \"thu\": [
     * {
     * \"start\": \"05:30\",
     * \"end\": \"20:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"02:30\",
     * \"end\": \"11:00\",
     * \"mode\": \"on\",
     * \"position\": 1
     * },
     * {
     * \"start\": \"17:30\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 2
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"08:00\",
     * \"mode\": \"on\",
     * \"position\": 3
     * }
     * ],
     * \"fri\": [
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 1
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 2
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 3
     * }
     * ],
     * \"sat\": [
     * {
     * \"start\": \"00:00\",
     * \"end\": \"23:30\",
     * \"mode\": \"on\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"00:30\",
     * \"end\": \"23:00\",
     * \"mode\": \"on\",
     * \"position\": 1
     * },
     * {
     * \"start\": \"01:00\",
     * \"end\": \"22:30\",
     * \"mode\": \"on\",
     * \"position\": 2
     * },
     * {
     * \"start\": \"01:30\",
     * \"end\": \"22:00\",
     * \"mode\": \"on\",
     * \"position\": 3
     * }
     * ],
     * \"sun\": [
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 1
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 2
     * },
     * {
     * \"start\": \"00:00\",
     * \"end\": \"24:00\",
     * \"mode\": \"on\",
     * \"position\": 3
     * }
     * ]
     * }"
     *
     * @param $schedule the schedule(see format above
     * @param null $circuitId
     */
    public
    function setRawCirculationSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::CIRCULATION_SCHEDULE), "setSchedule", $data);
    }

    /**
     * @param null $circuitId
     * @return json containing the Heating schedule for each days in format:
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
    public
    function getHeatingSchedule($circuitId = NULL)
    {
        return json_encode($this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::HEATING_SCHEDULE))->getProperties());
    }

    /**
     * Replace the full schedule for Heating. Sample of schedule
     * "{
     * \"mon\": [
     * {
     * \"start\": \"07:30\",
     * \"end\": \"22:00\",
     * \"mode\": \"normal\",
     * \"position\": 0
     * }
     * ],
     * \"tue\": [
     * {
     * \"start\": \"05:50\",
     * \"end\": \"22:00\",
     * \"mode\": \"normal\",
     * \"position\": 0
     * }
     * ],
     * \"wed\": [
     * {
     * \"start\": \"05:50\",
     * \"end\": \"22:00\",
     * \"mode\": \"normal\",
     * \"position\": 0
     * }
     * ],
     * \"thu\": [
     * {
     * \"start\": \"05:50\",
     * \"end\": \"22:00\",
     * \"mode\": \"normal\",
     * \"position\": 0
     * }
     * ],
     * \"fri\": [
     * {
     * \"start\": \"05:50\",
     * \"end\": \"08:00\",
     * \"mode\": \"normal\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"16:00\",
     * \"end\": \"22:00\",
     * \"mode\": \"normal\",
     * \"position\": 1
     * }
     * ],
     * \"sat\": [
     * {
     * \"start\": \"07:00\",
     * \"end\": \"22:00\",
     * \"mode\": \"normal\",
     * \"position\": 0
     * }
     * ],
     * \"sun\": [
     * {
     * \"start\": \"05:50\",
     * \"end\": \"12:00\",
     * \"mode\": \"normal\",
     * \"position\": 0
     * },
     * {
     * \"start\": \"18:00\",
     * \"end\": \"22:00\",
     * \"mode\": \"normal\",
     * \"position\": 1
     * }
     * ]
     * }"
     * @param null $circuitId
     * @param $schedule
     * @return array
     * @throws ViessmannApiException
     */
    public
    function setRawHeatingSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::HEATING_SCHEDULE), "setSchedule", $data);
    }

    public
    function getHeatingBurnerCurrentPower()
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_BURNER_CURRENT_POWER)->getProperty("value")["value"];
    }

    public
    function getHeatingBurnerModulation()
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_BURNER_MODULATION)->getProperty("value")["value"];
    }

    public
    function getCirculationPumpStatus($circuitId = NULL)
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::CIRCULATION_PUMP))->getProperty("status")["value"];
    }

    public
    function isDhwCharging(): bool
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_CHARGING)->getProperty("active")["value"];
    }

    public
    function getDhwChargingLevel(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_CHARGING_LEVEL)->getProperty("value")["value"];
    }

    public
    function isOneTimeDhwCharge(): bool
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_ONETIMECHARGE)->getProperty("active")["value"];
    }

    public
    function startOneTimeDhwCharge()
    {
        $data = "{}";
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_ONETIMECHARGE, "activate", $data);
    }

    public
    function stopOneTimeDhwCharge()
    {

        $data = "{}";
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_ONETIMECHARGE, "deactivate", $data);
    }

    public
    function getDhwPumpsCirculation(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_PUMPS_CIRCULATION)->getProperty("status")["value"];
    }

    public
    function getDhwPumpsPrimary(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_PUMPS_PRIMARY)->getProperty("status")["value"];
    }

    public
    function getDhwTemperatureOutlet(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_OUTLET)->getProperty("value")["value"];
    }

    public
    function getDhwTemperature(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_TEMPERATURE)->getProperty("value")["value"];
    }

    /**
     * @return String see https://en.wikipedia.org/wiki/Hysteresis
     * @throws ViessmannApiException
     */
    public
    function getDhwTemperatureHysteresis(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_TEMPERATURE_HYSTERESIS)->getProperty("value")["value"];
    }


    /**
     * @return String temperature of the return to the heating
     *
     */
    public
    function getHeatingTemperatureReturn(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_RETURN)->getProperty("value")["value"];
    }

    public
    function setDhwTemperature($temperature)
    {
        $data = "{\"temperature\": $temperature}";
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_TEMPERATURE, "setTargetTemperature", $data);
    }

    /**
     * @return String cooling mode
     */
    public
    function getHeatingConfigurationCoolingMode(): String
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_CONFIGURATION_COOLING)->getProperty("mode")["value"];
    }

    /**
     * @param $mode mode to set among 3 value: "none","natural","natural-mixer"
     * @return mixed
     */
    public
    function setHeatingConfigurationCoolingMode($mode)
    {
        {
            $data = "{\"mode\": $mode}";
            $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_CONFIGURATION_COOLING, "setMode", $data);
        }
    }

    /**
     * @return string last service if available
     * @throws ViessmannApiException
     */
    public
    function getLastServiceDate(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("lastService")["value"];
    }

    /**
     * @return number of month beetween service if available
     * @throws ViessmannApiException
     */
    public
    function getServiceInterval(): int
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("serviceIntervalMonths")["value"];
    }

    /**
     * @return number of month since service if available
     * @throws ViessmannApiException
     */
    public
    function getActiveMonthSinceService(): int
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("activeMonthSinceLastService")["value"];
    }


    private
    function buildHeatingCompressorLoadCassParameter($classNumber)
    {
        switch ($classNumber) {
            case 1:
                return "hoursLoadClassOne";
            case 2:
                return "hoursLoadClassTwo";
            case 3:
                return "hoursLoadClassThree";
            case 4:
                return "hoursLoadClassFour";
            case 5:
                return "hoursLoadClassFive";
            default:
                return NULL;
        }

    }


    private
    function buildFeature($circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return self::HEATING_CIRCUITS . "." . $circuitId . "." . $feature;
    }

    private
    function buildFeatureCompressors($circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return self::HEATING_COMPRESSORS . "." . $circuitId . "." . $feature;
    }

    public
    function getGenericFeaturePropertyAsJSON($feature, $properties = "value"): string
    {
        if(is_array($properties)) {
            $res = array();
            foreach($properties as $prop){
                $res[$prop] = $this->viessmannFeatureProxy->getEntity($feature)->getProperty($prop)["value"];
            }
        } else 
            $res = $this->viessmannFeatureProxy->getEntity($feature)->getProperty($properties)["value"];
        return json_encode($res);
    }

}
