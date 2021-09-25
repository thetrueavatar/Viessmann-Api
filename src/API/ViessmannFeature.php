<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 8/10/18
 * Time: 15:14
 */

namespace Viessmann\API;
/**
 * ViessmannFeture
 * @package Viessmann\API\ViessmannFeture
 */
final class ViessmannFeature
{
    const DEVICE_SERIAL = "device.serial";
    const DEVICE_TIMEZONE = "device.timezone";

    const GATEWAY_DEVICES = "gateway.devices";
    const GATEWAY_ETHERNET = "gateway.ethernet";
    const GATEWAY_ETHERNET_CONFIG = "gateway.ethernet.config";
    const GATEWAY_WIFI = "gateway.wifi";

    const HEATING_BOILER_SENSORS_TEMPERATURE_COMMONSUPPLY = "heating.boiler.sensors.temperature.commonSupply";
    const HEATING_BOILER_SENSORS_TEMPERATURE_MAIN = "heating.boiler.sensors.temperature.main";
    const HEATING_BOILER_SERIAL = "heating.boiler.serial";
    const HEATING_BOILER_TEMPERATURE = "heating.boiler.temperature";

    const HEATING_BURNER = "heating.burner";
    const HEATING_BURNER_MODULATION = "heating.burners.0.modulation"; 
    const HEATING_BURNER_STATISTICS = "heating.burners.0.statistics";

    const HEATING_CIRCUITS = "heating.circuits";

    const HEATING_CIRCUITS_0_CIRCULATION = "heating.circuits.0.circulation";
    const HEATING_CIRCUITS_0_CIRCULATION_PUMP = "heating.circuits.0.circulation.pump";
    const HEATING_CIRCUITS_0_CIRCULATION_SECONDARYPUMP = "heating.circuits.0.circulation.secondaryPump";
    const HEATING_CIRCUITS_0_PUMPS_CIRCULATION_SCHEDULE = "heating.circuits.0.pumps.circulation.schedule";
    const HEATING_CIRCUITS_0_DHW = "heating.circuits.0.dhw";
    const HEATING_CIRCUITS_0_DHW_PUMPS_CIRCULATION_SCHEDULE = "heating.circuits.0.dhw.pumps.circulation.schedule";
    const HEATING_CIRCUITS_0_DHW_SCHEDULE = "heating.circuits.0.dhw.schedule"; 
    const HEATING_CIRCUITS_0_FROSTPROTECTION = "heating.circuits.0.frostprotection";
    const HEATING_CIRCUITS_0 = "heating.circuits.0"; 
    const HEATING_CIRCUITS_0_HEATING_CURVE = "heating.circuits.0.heating.curve";
    const HEATING_CIRCUITS_0_HEATING = "heating.circuits.0.heating";
    const HEATING_CIRCUITS_0_HEATING_SCHEDULE = "heating.circuits.0.heating.schedule";
    
    const HEATING_CIRCUITS_0_OPERATING = "heating.circuits.0.operating";
    const HEATING_CIRCUITS_0_OPERATING_MODES = "heating.circuits.0.operating.modes";
    const HEATING_CIRCUITS_0_OPERATING_MODES_ACTIVE = "heating.circuits.0.operating.modes.active";
    const HEATING_CIRCUITS_0_OPERATING_MODES_COOLING = "heating.circuits.0.operating.modes.cooling";
    const HEATING_CIRCUITS_0_OPERATING_MODES_DHW = "heating.circuits.0.operating.modes.dhw";
    const HEATING_CIRCUITS_0_OPERATING_MODES_DHWANDHEATING = "heating.circuits.0.operating.modes.dhwAndHeating";
    const HEATING_CIRCUITS_0_OPERATING_MODES_DHWANDHEATINGCOOLING = "heating.circuits.0.operating.modes.dhwAndHeatingCooling";
    const HEATING_CIRCUITS_0_OPERATING_MODES_HEATING = "heating.circuits.0.operating.modes.heating";
    const HEATING_CIRCUITS_0_OPERATING_MODES_HEATINGCOOLING = "heating.circuits.0.operating.modes.heatingCooling";
    const HEATING_CIRCUITS_0_OPERATING_MODES_NORMALSTANDBY = "heating.circuits.0.operating.modes.normalStandby";
    const HEATING_CIRCUITS_0_OPERATING_MODES_STANDBY = "heating.circuits.0.operating.modes.standby";

    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_ACTIVE = "heating.circuits.0.operating.programs.active";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_COMFORT = "heating.circuits.0.operating.programs.comfort";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_ECO = "heating.circuits.0.operating.programs.eco";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_EXTERNAL = "heating.circuits.0.operating.programs.external";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_FIXED = "heating.circuits.0.operating.programs.fixed";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_FORCEDLASTSCHEDULE = "heating.circuits.0.operating.programs.forcedLastFromSchedule";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_HOLIDAY = "heating.circuits.0.operating.programs.holiday";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_HOLIDAYATHOME = "heating.circuits.0.operating.programs.holidayAtHome";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_NORMAL = "heating.circuits.0.operating.programs.normal";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_NODEMANDBYZERO = "heating.circuits.0.operating.programs.noDemandByZone";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_REDUCED = "heating.circuits.0.operating.programs.reduced";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_STANDBY = "heating.circuits.0.operating.programs.standby";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_SUMMERECO = "heating.circuits.0.operating.programs.summerEco";

    const HEATING_CIRCUITS_0_SENSORS_TEMPERATURE_ROOM = "heating.circuits.0.sensors.temperature.room";
    const HEATING_CIRCUITS_0_SENSORS_TEMPERATURE_SUPPLY = "heating.circuits.0.sensors.temperature.supply";

    const HEATING_CIRCUITS_0_TEMPERATURE = "heating.circuits.0.temperature";
    const HEATING_CIRCUITS_0_TEMPERATURE_LEVELS = "heating.circuits.0.temperature.levels";
    const HEATING_CIRCUITS_0_ZONE_DEMAND = "heating.circuits.0.zone.demand";
    const HEATING_CIRCUITS_0_ZONE_MODE = "heating.circuits.0.zone.mode";

    const HEATING_CIRCUITS_1_CIRCULATION = "heating.circuits.1.circulation";
    const HEATING_CIRCUITS_1_CIRCULATION_PUMP = "heating.circuits.1.circulation.pump";
    const HEATING_CIRCUITS_1_CIRCULATION_SECONDARYPUMP = "heating.circuits.1.circulation.secondaryPump";
    const HEATING_CIRCUITS_1_PUMPS_CIRCULATION_SCHEDULE = "heating.circuits.1.pumps.circulation.schedule";
    const HEATING_CIRCUITS_1_DHW = "heating.circuits.1.dhw";
    const HEATING_CIRCUITS_1_DHW_PUMPS_CIRCULATION_SCHEDULE = "heating.circuits.1.dhw.pumps.circulation.schedule";
    const HEATING_CIRCUITS_1_DHW_SCHEDULE = "heating.circuits.1.dhw.schedule"; 
    const HEATING_CIRCUITS_1_FROSTPROTECTION = "heating.circuits.1.frostprotection";
    const HEATING_CIRCUITS_1 = "heating.circuits.1"; 
    const HEATING_CIRCUITS_1_HEATING_CURVE = "heating.circuits.1.heating.curve";
    const HEATING_CIRCUITS_1_HEATING = "heating.circuits.1.heating";
    const HEATING_CIRCUITS_1_HEATING_SCHEDULE = "heating.circuits.1.heating.schedule";
    
    const HEATING_CIRCUITS_1_OPERATING = "heating.circuits.1.operating";
    const HEATING_CIRCUITS_1_OPERATING_MODES = "heating.circuits.1.operating.modes";
    const HEATING_CIRCUITS_1_OPERATING_MODES_ACTIVE = "heating.circuits.1.operating.modes.active";
    const HEATING_CIRCUITS_1_OPERATING_MODES_COOLING = "heating.circuits.1.operating.modes.cooling";
    const HEATING_CIRCUITS_1_OPERATING_MODES_DHW = "heating.circuits.1.operating.modes.dhw";
    const HEATING_CIRCUITS_1_OPERATING_MODES_DHWANDHEATING = "heating.circuits.1.operating.modes.dhwAndHeating";
    const HEATING_CIRCUITS_1_OPERATING_MODES_DHWANDHEATINGCOOLING = "heating.circuits.1.operating.modes.dhwAndHeatingCooling";
    const HEATING_CIRCUITS_1_OPERATING_MODES_HEATING = "heating.circuits.1.operating.modes.heating";
    const HEATING_CIRCUITS_1_OPERATING_MODES_HEATINGCOOLING = "heating.circuits.1.operating.modes.heatingCooling";
    const HEATING_CIRCUITS_1_OPERATING_MODES_NORMALSTANDBY = "heating.circuits.1.operating.modes.normalStandby";
    const HEATING_CIRCUITS_1_OPERATING_MODES_STANDBY = "heating.circuits.1.operating.modes.standby";

    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_ACTIVE = "heating.circuits.1.operating.programs.active";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_COMFORT = "heating.circuits.1.operating.programs.comfort";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_ECO = "heating.circuits.1.operating.programs.eco";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_EXTERNAL = "heating.circuits.1.operating.programs.external";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_FIXED = "heating.circuits.1.operating.programs.fixed";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_FORCEDLASTSCHEDULE = "heating.circuits.1.operating.programs.forcedLastFromSchedule";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_HOLIDAY = "heating.circuits.1.operating.programs.holiday";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_HOLIDAYATHOME = "heating.circuits.1.operating.programs.holidayAtHome";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_NORMAL = "heating.circuits.1.operating.programs.normal";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_NODEMANDBYZERO = "heating.circuits.N.operating.programs.noDemandByZone";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_REDUCED = "heating.circuits.1.operating.programs.reduced";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_STANDBY = "heating.circuits.1.operating.programs.standby";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_SUMMERECO = "heating.circuits.N.operating.programs.summerEco";

    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE_ROOM = "heating.circuits.1.sensors.temperature.room";
    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE_SUPPLY = "heating.circuits.1.sensors.temperature.supply";

    const HEATING_CIRCUITS_1_TEMPERATURE = "heating.circuits.1.temperature";
    const HEATING_CIRCUITS_1_TEMPERATURE_LEVELS = "heating.circuits.1.temperature.levels";
    const HEATING_CIRCUITS_1_ZONE_DEMAND = "heating.circuits.1.zone.demand";
    const HEATING_CIRCUITS_1_ZONE_MODE = "heating.circuits.1.zone.mode";

    const HEATING_COMPRESSORS = "heating.compressors";
    const HEATING_COMPRESSORS_0 = "heating.compressors.0";
    const HEATING_COMPRESSORS_0_STATISTICS = "heating.compressors.0.statistics";
    
    const HEATING_COMPRESSORS_1 = "heating.compressors.1";
    const HEATING_COMPRESSORS_1_STATISTICS = "heating.compressors.1.statistics";

    const HEATING_CONFIGURATION_MULTIFAMILYHOUSE = "heating.configuration.multiFamilyHouse";

    const HEATING_CONTROLLER_SERIAL = "heating.controller.serial";  

    const HEATING_DEVICE_TIME_OFFSET = "heating.device.time.offset";
    const HEATING_DEVICE_VARIANT = "heating.device.variant";

    const HEATING_DHW = "heating.dhw";
    const HEATING_DHW_CHARGING = "heating.dhw.charging";
    const HEATING_DHW_CHARGING_LEVEL = "heating.dhw.charging.level";
    const HEATING_DHW_COMFORT = "heating.dhw.comfort";
    const HEATING_DHW_ONETIMECHARGE = "heating.dhw.oneTimeCharge";
    const HEATING_DHW_PUMPS_CIRCULATION = "heating.dhw.pumps.circulation";
    const HEATING_DHW_PUMPS_CIRCULATION_SCHEDULE = "heating.dhw.pumps.circulation.schedule";
    const HEATING_DHW_PUMPS_PRIMARY = "heating.dhw.pumps.primary";
    const HEATING_DHW_SCHEDULE = "heating.dhw.schedule";
    const HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE = "heating.dhw.sensors.temperature.hotWaterStorage";
    const HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE_BOTTOM = "heating.dhw.sensors.temperature.hotWaterStorage.bottom";
    const HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE_MIDBOTTOM = "heating.dhw.sensors.temperature.hotWaterStorage.midBottom";
    const HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE_MIDDLE = "heating.dhw.sensors.temperature.hotWaterStorage.middle";
    const HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE_TOP = "heating.dhw.sensors.temperature.hotWaterStorage.top";
    const HEATING_DHW_SENSORS_TEMPERATURE_OUTLET = "heating.dhw.sensors.temperature.outlet";
    
    const HEATING_DHW_TEMPERATURE = "heating.dhw.temperature.main";
    const HEATING_DHW_TEMPERATURE_HYSTERESIS = "heating.dhw.temperature.hysteresis";
    const HEATING_DHW_TEMPERATURE_LEVELS = "heating.dhw.temperature.levels";
    const HEATING_DHW_TEMPERATURE_HYGIENE = "heating.dhw.temperature.hygiene";
    const HEATING_DHW_TEMPERATURE_MAIN = "heating.dhw.temperature.main";
    const HEATING_DHW_TEMPERATURE_TEMP2 =  "heating.dhw.temperature.temp2";

    const HEATING_GAS_CONSUMPTION_DHW = "heating.gas.consumption.dhw";
    const HEATING_GAS_CONSUMPTION_HEATING = "heating.gas.consumption.heating";
    const HEATING_GAS_CONSUMPTION_TOTAL = "heating.gas.consumption.total";

    const HEATING_INCREASEDRETURN_TEMPERATURE = "heating.increasedReturn.temperature";

    const HEATING_OPERATING_PROGRAMMS_HOLLIDAY = "heating.operating.programs.holiday";
    const HEATING_OPERATING_PROGRAMMS_HOLLIDAYATHOME = "heating.operating.programs.holidayAtHome";

    const HEATING_POWER_CONSUMPTION = "heating.power.consumption";
    const HEATING_POWER_CONSUMPTION_DHW = "heating.power.consumption.dhw";  
    const HEATING_POWER_CONSUMPTION_HEATING = "heating.power.consumption.heating";
    const HEATING_POWER_CONSUMPTION_TOTAL = "heating.power.consumption.total";

    const HEATING_SENSORS_TEMPERATURE_HYDRAULICSEPARATOR = "heating.sensors.temperature.hydraulicSeparator";
    const HEATING_SENSORS_TEMPERATURE_INCREASEDRETURN = "heating.sensors.temperature.increasedReturn";
    const HEATING_SENSORS_TEMPERATURE_OUTSIDE = "heating.sensors.temperature.outside";
    const HEATING_SENSORS_TEMPERATURE_RETURN = "heating.sensors.temperature.return";
    const HEATING_SENSORS_TEMPERATURE_SYSTEMRETURN = "heating.sensors.temperature.systemReturn";
    const HEATING_SENSORS_VALVE_BUFFERDISCHARGETHREEWAYVALVE = "heating.sensors.valve.bufferDischargeThreeWayValve";
    const HEATING_SENSORS_VOLUMETRICFLOW_RETURN = "heating.sensors.volumetricFlow.return";
    const HEATING_SOLAR_POWER_PRODUCTION = "heating.solar.power.production";
    const HEATING_SOLAR_PUMPS_CIRCUIT = "heating.solar.pumps.circuit";
    const HEATING_SOLAR_SENSORS_TEMPERATURE_COLLECTOR = "heating.solar.sensors.temperature.collector"; 
    const HEATING_SOLAR_SENSORS_TEMPERATURE_DHW = "heating.solar.sensors.temperature.dhw";
    const HEATING_SOLAR_STATISTICS = "heating.solar.statistics";

    const VENTILATION_OPERATING_MODES_ACTIVE = "ventilation.operating.modes.active";
    const VENTILATION_OPERATING_MODES_STANDARD = "ventilation.operating.modes.standard";
    const VENTILATION_OPERATING_MODES_STANDBY = "ventilation.operating.modes.standby";
    const VENTILATION_OPERATING_MODES_VENTILATION =  "ventilation.operating.modes.ventilation";
    const VENTILATION_OPERATING_PROGRAMMS_ACTIVE = "ventilation.operating.programs.active";
    const VENTILATION_OPERATING_PROGRAMMS_BASIC = "ventilation.operating.programs.basic";
    const VENTILATION_OPERATING_PROGRAMMS_INTENSIVE = "ventilation.operating.programs.intensive";
    const VENTILATION_OPERATING_PROGRAMMS_REDUCED = "ventilation.operating.programs.reduced";
    const VENTILATION_OPERATING_PROGRAMMS_STANDARD = "ventilation.operating.programs.standard";
    const VENTILATION_OPERATING_PROGRAMMS_STANDBY = "ventilation.operating.programs.standby";
    const VENTILATION_SCHEDULE = "ventilation.schedule";

    /**
     * [DEPRICATED FEATURES]
     */
    
    // const DEVICE_ETN = "device.etn";
    // const DEVICE_ZIGBEE_ACTIVE = "device.zigbee.active";
    // const DEVICE_ZIGBEE_STATUS = "device.zigbee.status";
    // const GATEWAY_BMU = "gateway.bmu";

    // const GATEWAY_FIRMWARE = "gateway.firmware";
    // const GATEWAY = "gateway";
    // const GATEWAY_LOGLEVEL = "gateway.logLevel";
    // const GATEWAY_STATUS = "gateway.status";

    // const HEATING_BOILER = "heating.boiler";
    // const HEATING_BOILER_SENSORS = "heating.boiler.sensors";

    // const HEATING_BURNER_AUTOMATIC = "heating.burners.automatic";
    // const HEATING_BURNER_CURRENT_POWER = "heating.burners.current.power";

    // const HEATING_CIRCUITS_0_CIRCULATION_SCHEDULE = "heating.circuits.0.circulation.schedule";

    // const HEATING_CIRCUITS_0_OPERATING_MODES_FORCEDNORMAL = "heating.circuits.0.operating.modes.forcedNormal";
    // const HEATING_CIRCUITS_0_OPERATING_MODES_FORCEDREDUCED = "heating.circuits.0.operating.modes.forcedReduced";

    // const HEATING_CIRCUITS_0_OPERATING_PROGRAMS = "heating.circuits.0.operating.programs";

    // const HEATING_CIRCUITS_0_SENSORS = "heating.circuits.0.sensors";
    // const HEATING_CIRCUITS_0_SENSORS_TEMPERATURE = "heating.circuits.0.sensors.temperature";

    /*
    const HEATING_CIRCUITS_1_CIRCULATION = "heating.circuits.1.circulation";
    const HEATING_CIRCUITS_1_CIRCULATION_PUMP = "heating.circuits.1.circulation.pump";
    const HEATING_CIRCUITS_1_CIRCULATION_SECONDARYPUMP = "heating.circuits.1.circulation.secondaryPump";
    const HEATING_CIRCUITS_1_PUMPS_CIRCULATION_SCHEDULE = "heating.circuits.1.pumps.circulation.schedule";
    const HEATING_CIRCUITS_1_DHW = "heating.circuits.1.dhw";
    const HEATING_CIRCUITS_1_DHW_SCHEDULE = "heating.circuits.1.dhw.schedule";
    const HEATING_CIRCUITS_1_FROSTPROTECTION = "heating.circuits.1.frostprotection";
    const HEATING_CIRCUITS_1 = "heating.circuits.1";
    const HEATING_CIRCUITS_1_HEATING_CURVE = "heating.circuits.1.heating.curve";
    const HEATING_CIRCUITS_1_HEATING = "heating.circuits.1.heating";
    const HEATING_CIRCUITS_1_HEATING_SCHEDULE = "heating.circuits.1.heating.schedule";
    const HEATING_CIRCUITS_1_OPERATING = "heating.circuits.1.operating";
    const HEATING_CIRCUITS_1_OPERATING_MODES_ACTIVE = "heating.circuits.1.operating.modes.active";
    const HEATING_CIRCUITS_1_OPERATING_MODES_COOLING = "heating.circuits.1.operating.modes.cooling";
    const HEATING_CIRCUITS_1_OPERATING_MODES_DHWANDHEATING = "heating.circuits.1.operating.modes.dhwAndHeating";
    const HEATING_CIRCUITS_1_OPERATING_MODES_DHW = "heating.circuits.1.operating.modes.dhw";
    const HEATING_CIRCUITS_1_OPERATING_MODES_FORCEDNORMAL = "heating.circuits.1.operating.modes.forcedNormal";
    const HEATING_CIRCUITS_1_OPERATING_MODES_FORCEDREDUCED = "heating.circuits.1.operating.modes.forcedReduced";
    const HEATING_CIRCUITS_1_OPERATING_MODES = "heating.circuits.1.operating.modes";
    const HEATING_CIRCUITS_1_OPERATING_MODES_STANDBY = "heating.circuits.1.operating.modes.standby";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_ACTIVE = "heating.circuits.1.operating.programs.active";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_COMFORT = "heating.circuits.1.operating.programs.comfort";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_ECO = "heating.circuits.1.operating.programs.eco";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_EXTERNAL = "heating.circuits.1.operating.programs.external";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS = "heating.circuits.1.operating.programs";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_HOLIDAY = "heating.circuits.1.operating.programs.holiday";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_NORMAL = "heating.circuits.1.operating.programs.normal";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_REDUCED = "heating.circuits.1.operating.programs.reduced";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_STANDBY = "heating.circuits.1.operating.programs.standby";
    const HEATING_CIRCUITS_1_SENSORS = "heating.circuits.1.sensors";
    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE = "heating.circuits.1.sensors.temperature";
    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE_ROOM = "heating.circuits.1.sensors.temperature.room";
    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE_SUPPLY = "heating.circuits.1.sensors.temperature.supply";
    */

    // const HEATING_CIRCUITS_1_CIRCULATION_SCHEDULE = "heating.circuits.1.circulation.schedule";

    // const HEATING_CIRCUITS_1_OPERATING_MODES_FORCEDNORMAL = "heating.circuits.1.operating.modes.forcedNormal";
    // const HEATING_CIRCUITS_1_OPERATING_MODES_FORCEDREDUCED = "heating.circuits.1.operating.modes.forcedReduced";

    // const HEATING_CIRCUITS_1_OPERATING_PROGRAMS = "heating.circuits.1.operating.programs";

    // const HEATING_CIRCUITS_1_SENSORS = "heating.circuits.1.sensors";
    // const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE = "heating.circuits.1.sensors.temperature";

    // const HEATING_CONFIGURATION_COOLING = "heating.configuration.cooling";

    // const HEATING_DEVICE = "heating.device";
    // const HEATING_DEVICE_TIME = "heating.device.time";

    // const HEATING_DHW_SENSORS = "heating.dhw.sensors";

    // const HEATING_ERRORS_ACTIVE = "heating.errors.active";
    // const HEATING_ERRORS = "heating.errors";
    // const HEATING_ERRORS_HISTORY = "heating.errors.history";

    /*
    const HEATING_FUELCELL_OPERATING_MODES_ACTIVE = "heating.fuelCell.operating.modes.active";
    const HEATING_FUELCELL_OPERATING_MODES_ECOLOGICAL = "heating.fuelCell.operating.modes.ecological";
    const HEATING_FUELCELL_OPERATING_MODES_ECONOMICAL = "heating.fuelCell.operating.modes.economical";
    const HEATING_FUELCELL_OPERATING_MODES_HEATCONTROLLED = "heating.fuelCell.operating.modes.heatControlled";
    const HEATING_FUELCELL_OPERATING_MODES_MAINTENANCE = "heating.fuelCell.operating.modes.maintenance";
    const HEATING_FUELCELL_OPERATING_MODES_STANDBY = "heating.fuelCell.operating.modes.standby";
    const HEATING_FUELCELL_OPERATING_PHASE = "heating.fuelCell.operating.phase";
    const HEATING_FUELCELL_POWER_PRODUCTION = "heating.fuelCell.power.production";
    const HEATING_FUELCELL_SENSORS_TEMPERATURE_RETURN = "heating.fuelCell.sensors.temperature.return";
    const HEATING_FUELCELL_SENSORS_TEMPERATURE_SUPPLY = "heating.fuelCell.sensors.temperature.supply";
    const HEATING_FUELCELL_STATISTICS = "heating.fuelCell.statistics";
    */

    // const HEATING_GAS_CONSUMPTION_FUELCELL = "heating.gas.consumption.fuelCell";
 
    // const HEATING = "heating";

    /*
    const HEATING_POWER_PRODUCTION_DEMANDCOVERAGE_CURRENT = "heating.power.production.demandCoverage.current";
    const HEATING_POWER_PRODUCTION_DEMANDCOVERAGE_TOTAL = "heating.power.production.demandCoverage.total";
    const HEATING_POWER_PRODUCTION = "heating.power.production";
    const HEATING_POWER_PRODUCTION_PRODUCTIONCOVERAGE_CURRENT = "heating.power.production.productionCoverage.current";
    const HEATING_POWER_PRODUCTION_PRODUCTIONCOVERAGE_TOTAL = "heating.power.production.productionCoverage.total";
    const HEATING_POWER_PURCHASE_CURRENT = "heating.power.purchase.current";
    const HEATING_POWER_SOLD_CURRENT = "heating.power.sold.current";
    const HEATING_POWER_SOLD = "heating.power.sold";
    const HEATING_PRIMARYCIRCUIT_SENSORS_TEMPERATURE_SUPPLY = "heating.primaryCircuit.sensors.temperature.supply";
    const HEATING_SECONDARYCIRCUIT_SENSORS_TEMPERATURE_RETURN = "heating.secondaryCircuit.sensors.temperature.return";
    const HEATING_SECONDARYCIRCUIT_SENSORS_TEMPERATURE_SUPPLY = "heating.secondaryCircuit.sensors.temperature.supply";
    */

    // const HEATING_SENSORS = "heating.sensors";

    // const HEATING_SENSORS_TEMPERATURE = "heating.sensors.temperature";

    // const HEATING_SERVICE = "heating.service";
    // const HEATING_SERVICE_TIMEBASED = "heating.service.timeBased";
    
    // const HEATING_SOLAR_POWER_CUMULATIVEPRODUCED = "heating.solar.power.cumulativeProduced";

    // const HEATING_SOLAR_RECHARGESUPPRESSION = "heating.solar.rechargeSuppression";

}
