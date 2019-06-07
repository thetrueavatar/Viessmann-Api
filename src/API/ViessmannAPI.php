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
    const HOLIDAY_PROGRAM = "operating.programs.holiday";
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
     * @return String containing a list of all the features having either a property either an action on it
     */
    public function getAvailableFeatures(): String
    {
        $features=$this->getEntity("");
        $classes="";

        foreach ($features->getEntities() as $feature) {
            if ($feature->getActions()!=NULL||$feature->getProperties()!=NULL){
                $classes=$classes.($feature->getClasses()[0])."\n";
            }
        }
        return $classes;
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
     * @return string Comfort program temperature programmed
     * @throws ViessmannApiException
     */
    public function getComfortProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::COMFORT_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param $temperature Comfort program temperature to program
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

    /**
     * Activate eco program
     * @param null $temperature optional temperature to set for eco program
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public function activateEcoProgram($temperature = NULL, $circuitId = NULL)
    {
        $data = NULL;
        if (isset($temperature)) {
            $data = "{\"temperature\":" . $temperature . "}";
        } else {
            $data = "{}";
        }
        $this->setRawJsonData($this->buildFeature($circuitId, self::ECO_PROGRAM), "activate", $data);
    }

    /**DeActivate eco program
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public function deActivateEcoProgram($circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::ECO_PROGRAM), "deactivate", "{}");
    }

    /**
     * schedule holiday program
     * start en end are in xml datetime format. See https://www.w3schools.com/xml/schema_dtypes_date.asp form more details
     * @param $start of holiday in xml datetime format
     * @param $end of holiday in datetime xml format
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public function scheduleHolidayProgram($start, $end, $circuitId = NULL)
    {
        $data = "{\"start\":\"" . $start . "\", \"end\":\"" . $end . "\"}";
        $this->setRawJsonData($this->buildFeature($circuitId, self::HOLIDAY_PROGRAM), "schedule", $data);
    }

    /** remove current holiday program's schedule
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public function unscheduleHolidayProgram($circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::HOLIDAY_PROGRAM), "unschedule", "{}");
    }

    /**
     * Activate Comfort program
     * @param null $temperature
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public function activateComfortProgram($temperature = NULL, $circuitId = NULL)
    {
        $data = NULL;
        if (isset($temperature)) {
            $data = "{\"temperature\":" . $temperature . "}";
        } else {
            $data = "{}";
        }
        $this->setRawJsonData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "activate", $data);
    }

    /**
     * Deactivate Comfort Program
     * @param null $circuitId
     * @throws ViessmannApiException
     */
    public function deActivateComfortProgram($circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::COMFORT_PROGRAM), "deactivate", "{}");
    }

    /**
     * @param null $circuitId
     * @return string External program temperature programmed
     * @throws ViessmannApiException
     */
    public function getExternalProgramTemperature($circuitId = NULL): string
    {
        return $this->getEntity($this->buildFeature($circuitId, self::EXTERNAL_PROGRAM))->getProperty("temperature")["value"];
    }

    /**
     * @param null $circuitId
     * @return string External program temperature to program
     * @throws ViessmannApiException
     */
    public function setExternalProgramTemperature($temperature, $circuitId = NULL)
    {
        $this->setRawJsonData($this->buildFeature($circuitId, self::EXTERNAL_PROGRAM), "setTemperature", "{\"targetTemperature\":" . $temperature . "}");
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
    public function getDhwSchedule(): string
    {
        return json_encode($this->getEntity(ViessmannFeature::HEATING_DHW_SCHEDULE)->getProperties());
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
    public function setRawDhwSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        $this->setRawJsonData($this->buildFeature($circuitId, self::DHW_SCHEDULE), "setSchedule", $data);
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
    public function getCirculationSchedule($circuitId = NULL): string
    {
        return json_encode($this->getEntity($this->buildFeature($circuitId, self::CIRCULATION_SCHEDULE))->getProperties());
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
    public function setRawCirculationSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        $this->setRawJsonData($this->buildFeature($circuitId, self::CIRCULATION_SCHEDULE), "setSchedule", $data);
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
    public function getHeatingSchedule($circuitId = NULL)
    {
        return json_encode($this->getEntity($this->buildFeature($circuitId, self::HEATING_SCHEDULE))->getProperties());
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
    public function setRawHeatingSchedule($schedule, $circuitId = NULL)
    {
        $data = "{\"newSchedule\": $schedule}";
        $this->setRawJsonData($this->buildFeature($circuitId, self::HEATING_SCHEDULE), "setSchedule", $data);
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

    public function isOneTimeDhwCharge(): bool
    {
        return $this->getEntity(ViessmannFeature::HEATING_DHW_ONETIMECHARGE)->getProperty("active")["value"];
    }

    public function startOneTimeDhwCharge()
    {
        $data = "{\"mode\": \"activate\"}";
        $this->setRawJsonData(ViessmannFeature::HEATING_DHW_ONETIMECHARGE, "activate", $data);
    }

    public function stopOneTimeDhwCharge()
    {

        $data = "{\"mode\": \"deactivate\"}";
        $this->setRawJsonData(ViessmannFeature::HEATING_DHW_ONETIMECHARGE, "deactivate", $data);
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

    /**
     * @return string last service if available
     * @throws ViessmannApiException
     */
    public function getLastServiceDate(): string
    {
        return $this->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("lastService")["value"];
    }

    /**
     * @return number of month beetween service if available
     * @throws ViessmannApiException
     */
    public function getServiceInterval(): int
    {
        return $this->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("serviceIntervalMonths")["value"];
    }

    /**
     * @return number of month since service if available
     * @throws ViessmannApiException
     */
    public function getActiveMonthSinceService(): int
    {
        return $this->getEntity(ViessmannFeature::HEATING_SERVICE_TIMEBASED)->getProperty("activeMonthSinceLastService")["value"];
    }


    public function setRawJsonData($feature, $action, $data)
    {
        try {
            $response = json_decode($this->viessmanAuthClient->setData($feature, $action, $data), true);
            if (isset($response["statusCode"])) {
                throw new ViessmannApiException("\n\t Unable to set data for feature" . $feature . " and action " . $action . " and data" . $data . "\n\t Reason: " . $response["message"], 1);
            }
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("\n\t Unable to set data for feature" . $feature . " and action " . $action . " and data" . $data . " \n\t Reason: " . $e->getMessage(), 1, $e);
        }
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
