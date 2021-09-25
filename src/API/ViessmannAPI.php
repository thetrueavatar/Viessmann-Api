<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 8/10/18
 * Time: 15:14
 */
namespace Viessmann\API;

use DateTime;
use TomPHP\Siren\Entity;
use Viessmann\API\proxy\impl\ViessmannFeatureLocalProxy;
use Viessmann\API\proxy\impl\ViessmannFeatureRemoteProxy;
use Viessmann\Oauth\ViessmannOauthClientImpl;

/**
 * ViessmannAPI
 * @package Viessmann\API\ViessmannAPI
 */
final class ViessmannAPI
{
    const HEATING_BURNER = "heating.burner";
    const HEATING_BURNERS = "heating.burners";
    const HEATING_CIRCUITS = "heating.circuits";
    const HEATING_COMPRESSORS = "heating.compressors";
    const HEATING_CURVE = "heating.curve";
    const HEATING_FROSTPROTECTION = "frostprotection";
    const HEATING_COMPRESSOR_STATISTICS = "statistics";
    const SENSORS_TEMPERATURE_ROOM = "sensors.temperature.room";
    const SENSORS_TEMPERATURE_SUPPLY = "sensors.temperature.supply";

    const OPERATING_MODES = "operating.modes.active";
    const COOLING_MODE = "operating.modes.cooling";
    const DHW_MODE = "operating.modes.dhw";
    const DHW_AND_HEATING_MODE = "operating.modes.dhwAndHeating";
    const DHW_AND_HEATING_COOLING_MODE = "operating.modes.dhwAndHeatingCooling";
    const HEATING_MODE = "operating.modes.heating";
    const HEATING_COOLING_MODE = "operating.modes.heatingCooling";
    const NORMALSTANDBY_MODE = "operating.modes.normalStandby";
    const STANDY_MODE = "operating.modes.standby";
    
    const ACTIVE_PROGRAM = "operating.programs.active";
    const COMFORT_PROGRAM = "operating.programs.comfort";
    const ECO_PROGRAM = "operating.programs.eco";
    const EXTERNAL_PROGRAM = "operating.programs.external";
    const FIXED_PROGRAM = "operating.programs.fixed";
    const FORCEDLASTSCHEDULE_PROGRAM = "operating.programs.forcedLastFromSchedule";
    const HOLIDAY_PROGRAM = "operating.programs.holiday";
    const HOLIDAYATHOME_PROGRAM = "operating.programs.holidayAtHome";
    const NORMAL_PROGRAM = "operating.programs.normal";
    const NODEMANDBYZERO_PROGRAM = "operating.programs.noDemandByZone";
    const REDUCED_PROGRAM = "operating.programs.reduced";
    const STANDBY_PROGRAM = "operating.programs.standby";
    const SUMMERECO_PROGRAM = "operating.programs.summerEco";

    const CIRCULATION_SCHEDULE = "circulation.schedule";
    const PUMPS_CIRCULATION_SCHEDULE = "pumps.circulation.schedule";
    const DHW_PUMPS_CIRCULATION_SCHEDULE = "dhw.pumps.circulation.schedule";

    const DHW_SCHEDULE = "dhw.schedule";
    const HEATING_SCHEDULE = "heating.schedule";
    const CIRCULATION_PUMP = "circulation.pump";
    const MODULATION = "modulation";
    const STATISTICS = "statistics";

    /**
     * circuitId
     */
    private $circuitId;

    /**
     * viessmannFeatureProxy
     */
    private $viessmannFeatureProxy;

    /**
     * viessmannOauthClient
     */
    private $viessmannOauthClient;
 
    /**
     * installationId
     */   
    private $installationId;

    /**
     * gatewayId
     */
    private $gatewayId;

    /**
     * ViessmannAPI constructor
     * @param $params
     * @param bool $useCache
     * @param $viessmannRemoteFeatureProxy
     * @param $viessmannOauthClient
     * @throws ViessmannApiException
     */
    public function __construct($params, $useCache = true, $viessmannRemoteFeatureProxy = NULL, $viessmannOauthClient = NULL)
    {
        $this->circuitId = $params["circuitId"] ?? 0;
        $this->viessmannOauthClient = $viessmannOauthClient ?? new ViessmannOauthClientImpl($params["user"], $params["pwd"],$params["clientId"]);
        if (!empty($params["installationId"]) && !empty($params["gatewayId"])) {
            $this->installationId = $params["installationId"];
            $this->gatewayId = $params["gatewayId"];
        } else {
            $installation = $this->getInstallationFormation();
            $this->installationId = $installation['installationId'];
            $this->gatewayId = $installation['gatewayId'];

        }
        $this->viessmannFeatureProxy = $viessmannRemoteFeatureProxy ?? new ViessmannFeatureRemoteProxy($this->viessmannOauthClient, $this->installationId, $this->gatewayId);

        if ($useCache) {
            $features = $this->viessmannFeatureProxy->getEntity("");
            $this->viessmannFeatureProxy = new ViessmannFeatureLocalProxy($features, $this->viessmannOauthClient, $this->installationId, $this->gatewayId);
        }
    }

    /**
     * getInstallationFormation
     * @return string
     * @throws ViessmannApiException
     */
    public function getInstallationFormation()
    {
        try {
            $response = json_decode($this->viessmannOauthClient->readData("equipment/gateways"), true);
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
            $installation = array('gatewayId' => $response['data'][0]['serial'],
                'installationId' => $response['data'][0]['installationId']);
            return $installation;
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);
        }
    }

    //TODO ADD support for gateway.ethernet and gateway.ethernet.config
    /**
     * getGatewayWifi
     * @return string
     * @throws ViessmannApiException
     */
    public function getGatewayWifi()
    {
        try {
            return $this->viessmannOauthClient->readData("equipment/installations/" . $this->getInstallationId() . "/gateways/" . $this->getGatewayId() . "/features/gateway.wifi");
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }
    
    /**
     * [DEPRICATED]
     * @return string
     * @throws ViessmannApiException
     */
    public function getGatewayFirmware()
    {
        try {
            return $this->viessmannOauthClient->readData("equipment/installations/" . $this->getInstallationId() . "/gateways/" . $this->getGatewayId() . "/features/gateway.firmware");
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }

    /**
     * [DEPRICATED]
     * @return string
     * @throws ViessmannApiException
     */
    public function getGatewayStatus()
    {
        try {
            return $this->viessmannOauthClient->readData("equipment/installations/" . $this->getInstallationId() . "/gateways/" . $this->getGatewayId() . "/features/gateway.status");
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }


    /**
     * [DEPRICATED]
     * @return string
     * @throws ViessmannApiException
    */
    public function getGatewayBmuconnection()
    {
        try {
            return $this->viessmannOauthClient->readData("equipment/installations/" . $this->getInstallationId() . "/gateways/" . $this->getGatewayId() . "/features/gateway.bmuconnection");
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);
        }
    }

    /**
     * getGatewayDevices
     * @return string
     * @throws ViessmannApiException
    */
    public function getGatewayDevices()
    {
        try {
            return $this->viessmannOauthClient->readData("equipment/installations/" . $this->getInstallationId() . "/gateways/" . $this->getGatewayId() . "/features/gateway.devices");
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to read installation basic information   \n\t Reason: " . $e->getMessage(), 2, $e);

        }
    }

    /**
     * setRawJsonData
     * @param $feature The feature to set
     * @param $action The action to execute
     * @param $data The data to pass to action
     *   // Exposes all setters. Less amount of code = less errors.
     * @throws ViessmannApiException
     */
    public
    function setRawJsonData($feature, $action, $data)
    {
        $this->viessmannFeatureProxy->setData($feature, $action, $data);
    }

    /**
     * getInstallationId
     * @return mixed
     * @throws ViessmannApiException
     */
    public
    function getInstallationId()
    {
        return $this->installationId;
    }

    /**
     * getGatewayId
     * @return mixed
     * @throws ViessmannApiException
     */
    public
    function getGatewayId()
    {
        return $this->gatewayId;
    }

    /**
     * getRawJsonData
     * @param $resources
     * @return json
     * @throws ViessmannApiException
     */
    public
    function getRawJsonData($resources): string
    {
        return $this->viessmannFeatureProxy->getRawJsonData($resources);
    }

    /**
     * getAvailableFeatures
     * @return String containing a list of all the features having either a property either an action on it
     * @throws ViessmannApiException
    */
    public
    function getAvailableFeatures(): string
    {
        $features = json_decode($this->viessmannFeatureProxy->getRawJsonData(""), true);
        return implode(",\n", $features);
    }


    /**
     * Outside temperature sensor
     * 
     * Shows information related to outside temperature sensor.
     * @return string the outside temperature if available
     * @throws ViessmannApiException
     */
    public
    function getOutsideTemperature(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_OUTSIDE)->getProperty("value")["value"];
    }

    /**
     * Boiler temperature sensor - Main
     * 
     * Shows information related with main temperature sensor.
     * @return string the current Boiler Temperature
     * @throws ViessmannApiException
     */
    public
    function getBoilerTemperature(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_BOILER_SENSORS_TEMPERATURE_MAIN)->getProperty("value")["value"];
    }

    /**
     * Sensor - system return temperature
     * 
     * Shows information about (Common return temperature) return sum of temperature of a cascade.
     * @return string the system return temperature
     * @throws ViessmannApiException
     */
    public
    function getTemperatureSystemReturn(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_SYSTEMRETURN)->getProperty("value")["value"];
    }

    /**
     * Sensor - buffer discharge
     * 
     * Shows information about percentage position of the buffer discharge 3-way valve.
     * @return string percentage position
     * @throws ViessmannApiException
     */
    public
    function getBufferDischargeThreeWayValvePercentage(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SENSORS_VALVE_BUFFERDISCHARGETHREEWAYVALVE)->getProperty("value")["value"];
    }
    
    /**
     * Sensor - return volumetric flow
     * 
     * Shows information about volumetric flow on the return.
     * @return string
     * @throws ViessmannApiException
     */
    public
    function getVolumetricFlowReturn(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SENSORS_VOLUMETRICFLOW_RETURN)->getProperty("value")["value"];
    }

    /**
     * 
     * Room temperature sensor
     * 
     * Shows information about room temperature sensor.
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
     * Heating curve (slope)
     * 
     * Shows values releted to heating curve (slope)
     * @param null $circuitId
     * @param null $features
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
     * Heating curve (Shift)
     * 
     * Shows values releted to heating curve (shift)
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
      * Set Heating curve
     * 
     * Set values releted to heating curve slope and shift
     * @param $shift the new shift to set
     * @param $slope the new slope to set
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function setCurve($shift, $slope, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::HEATING_CURVE), "setCurve", "{\"shift\":" . $shift . ",\"slope\":" . $slope . "}");
    }

    /**
     * Frost protection
     * 
     * Shows whether frost protection of installation is turn on in device.
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
     * Compressor start statistics
     * 
     * Shows statistics of compressor N: times started
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
     * Compressor Hour statistics
     * 
     * Shows statistics of compressor N: hours
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
     * getHeatingCompressorLoadClassHours
     * @param null $circuitId
     * @param null $classNumber (possible 1-5)
     * @return int the statistics for load classes 1-5
     * @throws ViessmannApiException
     */
    public
    function getHeatingCompressorLoadClassHours($circuitId = NULL, $classNumber = NULL): int
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeatureCompressors($circuitId, self::HEATING_COMPRESSOR_STATISTICS))->getProperty($this->buildHeatingCompressorLoadClassParameter($classNumber))["value"];
    }

    /**
     * Operating mode - active
     *
     * Shows current active operating mode on the device and provides command to change it.
     * @param null $circuitId
     * @return string the activeMode("cooling","dhw","dhwAndHeating","dhwAndHeatingCooling","heating","heatingCooling","normalStandby","standby")
     * @throws ViessmannApiException
     */
    public
    function getActiveMode($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::OPERATING_MODES))->getProperty("value")["value"];
    }

    /**
     * Operating mode - set
     * Set the active mode to the given mode
     * @param $mode the activeMode("cooling","dhw","dhwAndHeating","dhwAndHeatingCooling",<br>"heating","heatingCooling","normalStandby","standby")
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function setActiveMode($mode, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::OPERATING_MODES), "setMode", "{\"mode\":\"" . $mode . "\"}");
    }

    /**
     * Operating program - active
     * 
     * Shows current active operating program enabled on the device.
     * @param null $circuitId
     * @return string the active program("comfort","eco","external","fixed","forcedLastFromSchedule",<br>"holiday","holidayAtHome","normal","noDemandByZone","reduced","standby","summerEco")
     * @throws ViessmannApiException
     */
    public
    function getActiveProgram($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::ACTIVE_PROGRAM))->getProperty("value")["value"];
    }

    /**
     * Burner
     * 
     * Shows whether the burner N (0, 1, ...) is active right now.
     * @param null $circuitId optional / use circuit number when multiFamilyHouse is configured
     * @return bool true if heating burner is active. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isHeatingBurnerActive($circuitId = NULL): bool
    {
        if (is_null($circuitId)){
            return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_BURNER)->getProperty("active")["value"];
        } else {
            return $this->viessmannFeatureProxy->getEntity(self::HEATING_BURNERS . "." . $circuitId)->getProperty("active")["value"];
        }
    }

    /**
     * Compressors - N
     * 
     * Shows whether the compressor N (0, 1, ...) is active right now.
     * @param null $circuitId
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
     * Compressor statistics
     * 
     * Shows statistics of compressor N: hours and times started
     * @param null $circuitId
     * @return string statistics of the compressors
     * @throws ViessmannApiException
     */
    public
    function getHeatingCompressorsStatistics($circuitId = NULL): string
    {
        return json_encode($this->viessmannFeatureProxy->getEntity($this->buildFeatureCompressors($circuitId, self::STATISTICS))->getProperties());
    }

    /**
     * Operating mode - DHW
     * 
     * Shows whether the DHW operating mode is active now.
     * @param null $circuitId
     * @return bool true if DhwMode is active. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isDhwModeActive($circuitId = NULL): bool
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::DHW_MODE))->getProperty("active")["value"];
    }

    /**
     * Operating program - comfort
     * Shows information related to comfort program. 
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
     * Set Operating program - comfort
     * 
     * Provides the commands to enable/disable it and change the Comfort temperature value   
     * @todo how enable/disable??? 
     * @param $temperature Comfort program temperature to program
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function setComfortProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
    }

    /**
     * Operating program - eco
     * 
     * Shows whether Eco program is active.
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
     * Activate and set temperatur of Operating program - eco
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

    /**
     * Deactivate of Operating program - eco
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function deActivateEcoProgram($circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::ECO_PROGRAM), "deactivate", "{}");
    }

    /**
     * Operating program - holiday
     * 
     * Shows information related to Holiday program and provides command to set it.
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
     * Set Operating program - holiday
     * 
     * Provides the command to set it.
     * start and end are in xml datetime format. @see https://www.w3schools.com/xml/schema_dtypes_date.asp for more details
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

    /** 
     * Deactivate Operating program - holiday
     * 
     * Provides the command to enable it.
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function unscheduleHolidayProgram($circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::HOLIDAY_PROGRAM), "unschedule", "{}");
    }

    /**
     * Activate Operating program - comfort
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
     * Deactivate Operating program - comfort
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function deActivateComfortProgram($circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "deactivate", "{}");
    }

    /**
     * Operating program - external
     * Shows information related to External program, 
     * which is set when device is handled by external controller.
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
     * setExternalProgramTemperature
     * @param $temperature
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
     * Operating program - normal
     * 
     * Shows whether the Normal temperature program.
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
     * Set Operating program - normal
     * 
     * Shows whether the Normal temperature program.     
     * @param $temperature
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
     * getReducedProgramTemperature
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
     * setReducedProgramTemperature
     * @param $temperature
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
     * isInStandbyMode
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
     * isInFixedPrograms
     * @param null $circuitId
     * @return bool true if is Fixed. False otherwise
     * @throws ViessmannApiException
     */
    public
    function isInFixedPrograms($circuitId = NULL): bool
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::FIXED_PROGRAM))->getProperty("active")["value"];
    }

    /**
     * getSupplyTemperature
     * @param null $circuitId
     * @return string
     * @throws ViessmannApiException
     */
    public
    function getSupplyTemperature($circuitId = NULL): string
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::SENSORS_TEMPERATURE_SUPPLY))->getProperty("value")["value"];
    }


    /**
     * DHW temperature sensor
     * 
     * Shows information about hot water storage sensor.
     * @param string $position optional / amongst enume "bottom","midBottom","middle","top"
     * @return string Hot Water storage temperature
     * @throws ViessmannApiException
     */
    public
    function getHotWaterStorageTemperature($position = NULL): string
    {
        if (is_null($position)) {
            $feature = ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE;
        } else {
            $feature = ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE . "." . $position;
        }
        return $this->viessmannFeatureProxy->getEntity($feature)->getProperty("value")["value"];
    }


    /**
     * Return the Heating Solar Power production. A period is needed amongs day(default),week,month,year.
     * currently only day is returned
     * Currently the number
     * are not the same that displayed on heating device
     * @param string $period amongst enume "day","week","month","year
     * @return array <code>$period="day"</code>    - containing daily consommation for the last 7 days(each entry is consumption for a day)<br>
     *               <code>$period="week"</code>   - containing weekly consommation for the last 52 weeks(each entry is consumption for a week)<br>
     *               <code>$period="month"</code>  - containing monthly consommation for the last 12 month(each entry is consumption for one month)<br>
     *               <code>$period="year"</code>   - containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarPowerProduction($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_POWER_PRODUCTION)->getProperty($period)["value"];
    }


    /**
     * getHeatingSolarSensorsTemperatureCollector
     * @return string heating solar sensors temperature collector
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarSensorsTemperatureCollector(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_SENSORS_TEMPERATURE_COLLECTOR)->getProperty("value")["value"];
    }


    /** 
     * getHeatingSolarPowerCumulativeProduced [DEPRECATED]
     * @return string heating solar power cumulative produced in kWh
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarPowerCumulativeProduced(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_POWER_CUMULATIVEPRODUCED)->getProperty("value")["value"];
    }


    /**
     * getHeatingSolarSensorsTemperatureDhw
     * @return string heating solar sensors temperature dhw
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarSensorsTemperatureDhw(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_SENSORS_TEMPERATURE_DHW)->getProperty("value")["value"];
    }


    /** 
     * getHeatingSolarStatistics [DEPRECATED]
     * @return string heating solar system operational hours
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarStatistics(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_STATISTICS)->getProperty("hours")["value"];
    }


    /** 
     * getHeatingSolarRechargesuppression [DEPRECATED]
     * @return string off/on for recharge suppression
     * @throws ViessmannApiException
     */
    public
    function getHeatingSolarRechargesuppression(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SOLAR_RECHARGESUPPRESSION)->getProperty("status")["value"];
    }


    /**
     * getHeatingSolarPumpsCircuit
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
     * @return array <code>$period="day"</code>    - containing daily consommation for the last 7 days(each entry is consumption for a day)<br>
     *               <code>$period="week"</code>   - containing weekly consommation for the last 52 weeks(each entry is consumption for a week)<br>
     *               <code>$period="month"</code>  - containing monthly consommation for the last 12 month(each entry is consumption for one month)<br>
     *               <code>$period="year"</code>   - containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingPowerConsumption($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_POWER_CONSUMPTION_TOTAL)->getProperty($period)["value"];
    }

    /**
     * Power consumption - DHW
     * Shows statistics of power usage for heating up the DHW
     * @param string $period amongst enume "day","week","month","year
     * @return array <code>$period="day"</code>    - containing daily consommation for the last 7 days(each entry is consumption for a day)<br>
     *               <code>$period="week"</code>   - containing weekly consommation for the last 52 weeks(each entry is consumption for a week)<br>
     *               <code>$period="month"</code>  - containing monthly consommation for the last 12 month(each entry is consumption for one month)<br>
     *               <code>$period="year"</code>   - containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingPowerConsumptionDhw($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_POWER_CONSUMPTION_DHW)->getProperty($period)["value"];
    }

    /**
     * Power consumption - Heating
     * Shows statistics of power usage for heating up rooms
     * @param string $period amongst enume "day","week","month","year
     * @return array <code>$period="day"</code>    - containing daily consommation for the last 7 days(each entry is consumption for a day)<br>
     *               <code>$period="week"</code>   - containing weekly consommation for the last 52 weeks(each entry is consumption for a week)<br>
     *               <code>$period="month"</code>  - containing monthly consommation for the last 12 month(each entry is consumption for one month)<br>
     *               <code>$period="year"</code>   - containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingPowerConsumptionHeating($period = "day")
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_POWER_CONSUMPTION_HEATING)->getProperty($period)["value"];
    }

    /**
     * Return the Gas consumption for DHW. A period is needeed amongs day(default),week,month,year. Currently the number are not the same that displayed on heating device
     * if addUnit is true then the return will be a json object with unit and value
     * @param string $period amongst enume "day","week","month","year
     * @param bool $addUnit add Unit 
     * @return array <code>$period="day"</code>    - containing daily consommation for the last 7 days(each entry is consumption for a day)<br>
     *               <code>$period="week"</code>   - containing weekly consommation for the last 52 weeks(each entry is consumption for a week)<br>
     *               <code>$period="month"</code>  - containing monthly consommation for the last 12 month(each entry is consumption for one month)<br>
     *               <code>$period="year"</code>   - containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getDhwGasConsumption($period = "day", $addUnit = false)
    {
        if ($addUnit) {
            $data['unit'] = $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_DHW)->getProperty("unit")["value"];
            $data['value'] = $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_DHW)->getProperty($period)["value"];
            return json_encode($data);
        } else {
            return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_DHW)->getProperty($period)["value"];
        }
    }

    /**
     * Return the Gas consumption for Heating. A period is needeed amongs day(default),week,month,year. Currently the number are not the same that displayed on heating device
     * if addUnit is true then the return will be a json object with unit and value
     * @param string $period amongst enume "day","week","month","year
     * @param bool $addUnit add Unit 
     * @return array <code>$period="day"</code>    - containing daily consommation for the last 7 days(each entry is consumption for a day)<br>
     *               <code>$period="week"</code>   - containing weekly consommation for the last 52 weeks(each entry is consumption for a week)<br>
     *               <code>$period="month"</code>  - containing monthly consommation for the last 12 month(each entry is consumption for one month)<br>
     *               <code>$period="year"</code>   - containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingGasConsumption($period = "day", $addUnit = false)
    {
        if ($addUnit) {
            $data['unit'] = $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_HEATING)->getProperty("unit")["value"];
            $data['value'] = $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_HEATING)->getProperty($period)["value"];
            return json_encode($data);
        } else {
            return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_HEATING)->getProperty($period)["value"];

        }
    }

    /**
     * Return the Gas consumption for Total Heating. A period is needeed amongs day(default),week,month,year. Currently the number are not the same that displayed on heating device
     * if addUnit is true then the return will be a json object with unit and value
     * @param string $period amongst enume "day","week","month","year
     * @param bool $addUnit add Unit 
     * @return array <code>$period="day"</code>    - containing daily consommation for the last 7 days(each entry is consumption for a day)<br>
     *               <code>$period="week"</code>   - containing weekly consommation for the last 52 weeks(each entry is consumption for a week)<br>
     *               <code>$period="month"</code>  - containing monthly consommation for the last 12 month(each entry is consumption for one month)<br>
     *               <code>$period="year"</code>   - containing yearly consommation for the last 2 years(each entry is consumption for one year)
     * @throws ViessmannApiException
     */
    public
    function getHeatingGasConsumptionTotal($period = "day", $addUnit = false)
    {
        if ($addUnit) {
            $data['unit'] = $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_TOTAL)->getProperty("unit")["value"];
            $data['value'] = $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_TOTAL)->getProperty($period)["value"];
            return json_encode($data);
        } else {
            return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_GAS_CONSUMPTION_TOTAL)->getProperty($period)["value"];

        }
    }

    /**
     * getHeatingBurnerStatistics
     * @param string $type the type of statistics("hours":number of active hours or "starts": number of start)
     * @param $circuitId
     * @return mixed number of hours or number of starts
     * @throws ViessmannApiException
     */
    public
    function getHeatingBurnerStatistics($type = "hours", $circuitId = NULL)
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeatureBurners($circuitId, self::HEATING_BURNERS))->getProperty($type)["value"];
    }

    /**
     * getDhwSchedule
     * @param null $circuitId optional / use circuit number when multiFamilyHouse is configured
     * @return json containing the Dhw schedule for each days in format:
     * <pre>
     * "mon": 
     * [
     *   {
     *      "start": "03:00",
     *      "end": "24:00",
     *      "mode": "on",
     *      "position": 1
     *   }
     * ]
     * </pre>
     * @throws ViessmannApiException
     */
    public
    function getDhwSchedule($circuitId = NULL): string
    {   
        if (is_null($circuitId)){
            return json_encode($this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_SCHEDULE)->getProperties());
        } else {
            return json_encode($this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::DHW_SCHEDULE))->getProperties());  
        }
    }

    /**
     * Replace the full schedule for DHW. Sample of schedule:
     * 
     * <pre>
     * "{
     *  \"mon\": [
     *      {
     *          \"start\": \"03:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      }
     *  ],
     *  \"tue\": [
     *      {
     *          \"start\": \"03:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"wed\": [
     *      {
     *          \"start\": \"02:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      }
     *  ],
     *  \"thu\": [
     *      {
     *          \"start\": \"03:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"fri\": [
     *      {
     *          \"start\": \"03:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 4
     *      }
     *  ],
     *  \"sat\": [
     *      {
     *          \"start\": \"03:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"sun\": [
     *      {
     *          \"start\": \"03:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      }
     *  ]
     * }"
     * </pre>
     * @param $schedule
     * @param null $circuitId optional / use circuit number when multiFamilyHouse is configured
     * @return array
     * @throws ViessmannApiException
     */
    public
    function setRawDhwSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        if (is_null($circuitId)){
            $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_SCHEDULE, "setSchedule", $data);
        } else {
            $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::DHW_SCHEDULE), "setSchedule", $data);
        }
    }

    /**
     * getCirculationSchedule
     * 
     * Only available when multiFamilyHouse is configured
     * 
     * @param null $circuitId
     * @return json containing the Circulation schedule for each days in format:
     * <pre>
     * "mon": [
     *   {
     *      "start": "03:00",
     *      "end": "24:00",
     *      "mode": "on",
     *      "position": 1
     *   }
     * ]
     * </pre>
     * @throws ViessmannApiException
     */
    public
    function getCirculationSchedule($circuitId = NULL): string
    {
        return json_encode($this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::CIRCULATION_SCHEDULE))->getProperties());
    }

    /**
     * getDhwPumpsCirculationSchedule
     * @param null $circuitId optional / use circuit number when multiFamilyHouse is configured
     * @return json containing the Circulation schedule for each days in format:
     * <pre>
     * "mon": [
     *   {
     *      "start": "03:00",
     *      "end": "24:00",
     *      "mode": "on",
     *      "position": 1
     *   }
     * ]
     * </pre>
     * @throws ViessmannApiException
     */
    public
    function getDhwPumpsCirculationSchedule($circuitId = NULL): string
    {
        if (is_null($circuitId)){
            return json_encode($this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_PUMPS_CIRCULATION_SCHEDULE)->getProperties());
        } else {
            return json_encode($this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::DHW_PUMPS_CIRCULATION_SCHEDULE))->getProperties());
        }
    }

    /**
     * Post a complete new schedule. Warning !!! this would erase all previous schedule. Sample:
     * 
     * <pre>
     * "{
     *  \"mon\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *  },
     *  {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"tue\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"23:50\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"00:10\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"23:20\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"05:30\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *          }
     *  ],
     *  \"wed\": [
     *      {
     *          \"start\": \"05:30\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"thu\": [
     *      {
     *          \"start\": \"05:30\",
     *          \"end\": \"20:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"02:30\",
     *          \"end\": \"11:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"17:30\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"08:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"fri\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"sat\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"23:30\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:30\",
     *          \"end\": \"23:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"01:00\",
     *          \"end\": \"22:30\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"01:30\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"sun\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *           \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ]
     * }"
     * </pre>
     * @param $schedule the schedule(see format above)
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public
    function setRawCirculationSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::CIRCULATION_SCHEDULE), "setSchedule", $data);
    }

    /**
     * Post a complete new schedule. Warning !!! this would erase all previous schedule. Sample:
     * 
     * <pre>
     * "{
     *  \"mon\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *  },
     *  {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"tue\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"23:50\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"00:10\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"23:20\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"05:30\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *          }
     *  ],
     *  \"wed\": [
     *      {
     *          \"start\": \"05:30\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"thu\": [
     *      {
     *          \"start\": \"05:30\",
     *          \"end\": \"20:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"02:30\",
     *          \"end\": \"11:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"17:30\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"08:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"fri\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"sat\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"23:30\",
     *          \"mode\": \"on\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:30\",
     *          \"end\": \"23:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"01:00\",
     *          \"end\": \"22:30\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \"start\": \"01:30\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ],
     *  \"sun\": [
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *           \"position\": 0
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 1
     *      },
     *      {
     *          \"start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 2
     *      },
     *      {
     *          \start\": \"00:00\",
     *          \"end\": \"24:00\",
     *          \"mode\": \"on\",
     *          \"position\": 3
     *      }
     *  ]
     * }"
     * </pre>
     * @param $schedule the schedule(see format above)
     * @param null $circuitId optional / use circuit number when multiFamilyHouse is configured
     * @throws ViessmannApiException
     */
    public
    function setRawDhwPumpsCirculationSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        if (is_null($circuitId)){
            $this->viessmannFeatureProxy->setData($this->buildFeature(ViessmannFeature::HEATING_DHW_PUMPS_CIRCULATION_SCHEDULE), "setSchedule", $data);
        } else {
            $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::DHW_PUMPS_CIRCULATION_SCHEDULE), "setSchedule", $data);
        }
    }

    /**
     * getHeatingSchedule
     * @param null $circuitId
     * @return json containing the Heating schedule for each days in format:
     * <pre>
     * "mon": [
     *   {
     *      "start": "03:00",
     *      "end": "24:00",
     *      "mode": "on",
     *      "position": 1
     *   }
     * ]
     * </pre>
     * @throws ViessmannApiException
     */
    public
    function getHeatingSchedule($circuitId = NULL)
    {
        return json_encode($this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::HEATING_SCHEDULE))->getProperties());
    }

    /**
     * Replace the full schedule for Heating. Sample of schedule
     * 
     * <pre>
     * "{
     *  \"mon\": [
     *      {
     *          \"start\": \"07:30\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"tue\": [
     *      {
     *          \"start\": \"05:50\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"wed\": [
     *      {
     *          \"start\": \"05:50\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"thu\": [
     *      {
     *          \"start\": \"05:50\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"fri\": [
     *      {
     *          \"start\": \"05:50\",
     *          \"end\": \"08:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"16:00\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 1
     *      }
     *  ],
     *  \"sat\": [
     *      {
     *          \"start\": \"07:00\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 0
     *      }
     *  ],
     *  \"sun\": [
     *      {
     *          \"start\": \"05:50\",
     *          \"end\": \"12:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 0
     *      },
     *      {
     *          \"start\": \"18:00\",
     *          \"end\": \"22:00\",
     *          \"mode\": \"normal\",
     *          \"position\": 1
     *      }
     *  ]
     * }"
     * </pre>
     * @param $schedule
     * @param null $circuitId
     * @return array
     * @throws ViessmannApiException
     */
    public
    function setRawHeatingSchedule($schedule, $circuitId = NULL)
    {
        $this->viessmannFeatureProxy->setData($this->buildFeature($circuitId, self::HEATING_SCHEDULE), "setSchedule", "{\"newSchedule\": $schedule}");
    }

    /**
     * getHeatingBurnerModulation
     * @param null $circuitId
     * @return array
     * @throws ViessmannApiException
    */
    public
    function getHeatingBurnerModulation($circuitId = NULL)
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeatureBurners($circuitId, self::MODULATION))->getProperty("value")["value"];
    }

    /**
     * getCirculationPumpStatus
     * @param null $circuitId
     * @return string
     * @throws ViessmannApiException
     */
    public
    function getCirculationPumpStatus($circuitId = NULL)
    {
        return $this->viessmannFeatureProxy->getEntity($this->buildFeature($circuitId, self::CIRCULATION_PUMP))->getProperty("status")["value"];
    }

    /**
     * isDhwCharging
     * @param null $circuitId
     * @return bool
     * @throws ViessmannApiException
    */
    public
    function isDhwCharging(): bool
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_CHARGING)->getProperty("active")["value"];
    }

    /**
     * getDhwChargingLevel
     * @param null $circuitId
     * @return string
     * @throws ViessmannApiException
     */
    public
    function getDhwChargingLevel(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_CHARGING_LEVEL)->getProperty("value")["value"];
    }

    /**
     * isOneTimeDhwCharge
     * @param null $circuitId
     * @return bool
     * @throws ViessmannApiException
     */
    public
    function isOneTimeDhwCharge(): bool
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_ONETIMECHARGE)->getProperty("active")["value"];
    }

    /**
     * startOneTimeDhwCharge
     * @param null $circuitId
     * @return bool
     * @throws ViessmannApiException
     */
    public
    function startOneTimeDhwCharge()
    {
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_ONETIMECHARGE, "activate", "{}");
    }

    /**
     * stopOneTimeDhwCharge
     * @param null $circuitId
     * @return bool
     * @throws ViessmannApiException
     */
    public
    function stopOneTimeDhwCharge()
    {
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_ONETIMECHARGE, "deactivate", "{}");
    }

    /**
     * getDhwPumpsCirculation
     * @return String
     * @throws ViessmannApiException
     */
    public
    function getDhwPumpsCirculation(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_PUMPS_CIRCULATION)->getProperty("status")["value"];
    }

    /**
     * getDhwPumpsPrimary
     * @return String
     * @throws ViessmannApiException
     */
    public
    function getDhwPumpsPrimary(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_PUMPS_PRIMARY)->getProperty("status")["value"];
    }

    /**
     * getDhwTemperatureOutlet
     * @return String
     * @throws ViessmannApiException
     */
    public
    function getDhwTemperatureOutlet(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_SENSORS_TEMPERATURE_OUTLET)->getProperty("value")["value"];
    }

    /**
     * getDhwTemperature
     * @return String
     * @throws ViessmannApiException
     */
    public
    function getDhwTemperature(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_TEMPERATURE)->getProperty("value")["value"];
    }

    /**
     * DHW temperature hysteresis
     * 
     * Shows the hysteresis value of the Domestic Hot Water temperature in heat pumps. 
     * @todo Also provides the command to set it -> create setDhwTemperatureHysteresis(): string
     * @see https://en.wikipedia.org/wiki/Hysteresis
     * @return String https://en.wikipedia.org/wiki/Hysteresis
     * @throws ViessmannApiException
     */
    public
    function getDhwTemperatureHysteresis(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_TEMPERATURE_HYSTERESIS)->getProperty("value")["value"];
    }

    /**
     * DHW temperature hygiene
     * 
     * To kill Legionella bacteria the system needs to heat up to at least 65 °C
     * @return String
     * @throws ViessmannApiException
     */
    public
    function getDhwTemperatureHygiene(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_TEMPERATURE_HYGIENE)->getProperty("value")["value"];
    }


    /**
     * DHW temperature 2
     * 
     * For controllers with multiple possible dhw-setpoints. 
     * Shows the desired value of the Domestic Hot Water Temp 2 temperature. 
     * @return String
     * @throws ViessmannApiException
     */
    public
    function getDhwTemperatureTemp2(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_DHW_TEMPERATURE_TEMP2)->getProperty("value")["value"];
    }

    /**
     * Set DHW temperature 2
     * 
     * For controllers with multiple possible dhw-setpoints. 
     * Shows the desired value of the Domestic Hot Water Temp 2 temperature. 
     * @param $temperature
     * @throws ViessmannApiException
     */
    public
    function setDhwTemperatureTemp2($temperature)
    {
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_TEMPERATURE_TEMP2, "setTargetTemperature", "{\"temperature\": $temperature}");
    }

    /**
     * Flow return temperature sensor
     * 
     * Shows information about flow return temperature sensor, i.e. water temperature on return to the boiler from heating installation.
     * @return String temperature of the return to the heating
     * @throws ViessmannApiException
     */
    public
    function getHeatingTemperatureReturn(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SENSORS_TEMPERATURE_RETURN)->getProperty("value")["value"];
    }

    /**
     * Set DHW main temperature
     * 
     * Provides the command to set it
     * @param $temperature
     * @throws ViessmannApiException
     */
    public
    function setDhwTemperature($temperature)
    {
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_DHW_TEMPERATURE, "setTargetTemperature", "{\"temperature\": $temperature}");
    }

    /**
     * getHeatingConfigurationCoolingMode [DEPRICATED]
     * @return String cooling mode
     * @throws ViessmannApiException
     */
    public
    function getHeatingConfigurationCoolingMode(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_CONFIGURATION_COOLING)->getProperty("mode")["value"];
    }

    /**
     * setHeatingConfigurationCoolingMode
     * @param $mode mode to set among 3 value: "none","natural","natural-mixer"
     * @return mixed
     * @throws ViessmannApiException
     */
    public
    function setHeatingConfigurationCoolingMode($mode)
    {
        $this->viessmannFeatureProxy->setData(ViessmannFeature::HEATING_CONFIGURATION_COOLING, "setMode", "{\"mode\": $mode}");
    }

    /**
     * getLastServiceDate [DEPRICATED]
     * @return string last service if available
     * @throws ViessmannApiException
     */
    public
    function getLastServiceDate(): string
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("lastService")["value"];
    }

    /**
     * [getServiceInterval DEPRICATED]
     * @return number of month beetween service if available
     * @throws ViessmannApiException
     */
    public
    function getServiceInterval(): int
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("serviceIntervalMonths")["value"];
    }

    /**
     * getActiveMonthSinceService [DEPRICATED]
     * @return number of month since service if available
     * @throws ViessmannApiException
     */
    public
    function getActiveMonthSinceService(): int
    {
        return $this->viessmannFeatureProxy->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("activeMonthSinceLastService")["value"];
    }

    /**
     * buildHeatingCompressorLoadClassParameter
     * @param int $classNumber
     * @return string
     * @throws ViessmannApiException
     */
    private
    function buildHeatingCompressorLoadClassParameter($classNumber)
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

    /**
     * buildFeature
     * @param $circuitId
     * @param $feature
     * @return string
     * @throws ViessmannApiException
     */
    private
    function buildFeature($circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return self::HEATING_CIRCUITS . "." . $circuitId . "." . $feature;
    }

    /**
     * buildFeatureBurners
     * @param $circuitId
     * @param $feature
     * @return string
     * @throws ViessmannApiException
     */
    private
    function buildFeatureBurners($circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return self::HEATING_BURNERS . "." . $circuitId . "." . $feature;
    }

    /**
     * buildFeatureCompressors
     * @param $circuitId
     * @param $feature
     * @return string
     * @throws ViessmannApiException
     */
    private
    function buildFeatureCompressors($circuitId, $feature)
    {
        if ($circuitId == NULL) {
            $circuitId = $this->circuitId;
        }
        return self::HEATING_COMPRESSORS . "." . $circuitId . "." . $feature;
    }

    /**
     * getGenericFeaturePropertyAsJSON
     * @param $feature The feature to query
     * @param mixed $properties string or array describing properties to query
     *   // Exposes all getters. Less amount of code = less errors.
     * @return generic feature and property/properties as JSON
     * @throws ViessmannApiException
     */
    public
    function getGenericFeaturePropertyAsJSON($feature, $properties = "value"): string
    {
        if (is_array($properties)) {
            $res = array();
            foreach ($properties as $prop) {
                $res[$prop] = $this->viessmannFeatureProxy->getEntity($feature)->getProperty($prop)["value"];
            }
        } else
            $res = $this->viessmannFeatureProxy->getEntity($feature)->getProperty($properties)["value"];
        return json_encode($res);
    }

    /**
     * getProperties
     * @param $feature The feature to query
     * @return generic feature and property/properties as JSON
     * @throws ViessmannApiException
     */
    public
    function getProperties($feature): string
    {
        return json_encode($this->viessmannFeatureProxy->getEntity($feature)->getProperties());
    }
}
