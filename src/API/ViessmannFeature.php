<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 8/10/18
 * Time: 15:14
 */

namespace Viessmann\API;


final class ViessmannFeature
{
    const GATEWAY_BMU = "gateway.bmu";
    const GATEWAY_DEVICES = "gateway.devices";
    const GATEWAY_FIRMWARE = "gateway.firmware";
    const GATEWAY_LOGLEVEL = "gateway.logLevel";
    const GATEWAY = "gateway";
    const GATEWAY_STATUS = "gateway.status";
    const GATEWAY_WIFI = "gateway.wifi";
    const HEATING_BOILER_SENSORS = "heating.boiler.sensors";
    const HEATING_BOILER_SENSORS_TEMPERATURE_COMMONSUPPLY = "heating.boiler.sensors.temperature.commonSupply";
    const HEATING_BOILER_SENSORS_TEMPERATURE_MAIN = "heating.boiler.sensors.temperature.main";
    const HEATING_BOILER_SERIAL = "heating.boiler.serial";
    const HEATING_BOILER = "heating.boiler";
    const HEATING_BOILER_TEMPERATURE = "heating.boiler.temperature";
    const HEATING_BURNER_AUTOMATIC = "heating.burner.automatic";
    const HEATING_BURNER_CURRENT_POWER = "heating.burner.current.power";
    const HEATING_BURNER_MODULATION = "heating.burner.modulation";
    const HEATING_BURNER = "heating.burner";
    const HEATING_BURNER_STATISTICS = "heating.burner.statistics";
    const HEATING_CIRCUITS_0_CIRCULATION_PUMP = "heating.circuits.0.circulation.pump";
    const HEATING_CIRCUITS_0_CIRCULATION_SCHEDULE = "heating.circuits.0.circulation.schedule";
    const HEATING_CIRCUITS_0_CIRCULATION = "heating.circuits.0.circulation";
    const HEATING_CIRCUITS_0_DHW_SCHEDULE = "heating.circuits.0.dhw.schedule";
    const HEATING_CIRCUITS_0_DHW = "heating.circuits.0.dhw";
    const HEATING_CIRCUITS_0_FROSTPROTECTION = "heating.circuits.0.frostprotection";
    const HEATING_CIRCUITS_0_HEATING_CURVE = "heating.circuits.0.heating.curve";
    const HEATING_CIRCUITS_0_HEATING_SCHEDULE = "heating.circuits.0.heating.schedule";
    const HEATING_CIRCUITS_0_HEATING = "heating.circuits.0.heating";
    const HEATING_CIRCUITS_0_OPERATING_MODES_ACTIVE = "heating.circuits.0.operating.modes.active";
    const HEATING_CIRCUITS_0_OPERATING_MODES_DHW = "heating.circuits.0.operating.modes.dhw";
    const HEATING_CIRCUITS_0_OPERATING_MODES_DHWANDHEATING = "heating.circuits.0.operating.modes.dhwAndHeating";
    const HEATING_CIRCUITS_0_OPERATING_MODES_FORCEDNORMAL = "heating.circuits.0.operating.modes.forcedNormal";
    const HEATING_CIRCUITS_0_OPERATING_MODES_FORCEDREDUCED = "heating.circuits.0.operating.modes.forcedReduced";
    const HEATING_CIRCUITS_0_OPERATING_MODES = "heating.circuits.0.operating.modes";
    const HEATING_CIRCUITS_0_OPERATING_MODES_STANDBY = "heating.circuits.0.operating.modes.standby";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_ACTIVE = "heating.circuits.0.operating.programs.active";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_COMFORT = "heating.circuits.0.operating.programs.comfort";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_ECO = "heating.circuits.0.operating.programs.eco";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_EXTERNAL = "heating.circuits.0.operating.programs.external";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_HOLIDAY = "heating.circuits.0.operating.programs.holiday";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_NORMAL = "heating.circuits.0.operating.programs.normal";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_REDUCED = "heating.circuits.0.operating.programs.reduced";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS = "heating.circuits.0.operating.programs";
    const HEATING_CIRCUITS_0_OPERATING_PROGRAMS_STANDBY = "heating.circuits.0.operating.programs.standby";
    const HEATING_CIRCUITS_0_OPERATING = "heating.circuits.0.operating";
    const HEATING_CIRCUITS_0_SENSORS = "heating.circuits.0.sensors";
    const HEATING_CIRCUITS_0_SENSORS_TEMPERATURE_ROOM = "heating.circuits.0.sensors.temperature.room";
    const HEATING_CIRCUITS_0_SENSORS_TEMPERATURE = "heating.circuits.0.sensors.temperature";
    const HEATING_CIRCUITS_0_SENSORS_TEMPERATURE_SUPPLY = "heating.circuits.0.sensors.temperature.supply";
    const HEATING_CIRCUITS_0 = "heating.circuits.0";
    const HEATING_CIRCUITS_1_CIRCULATION_PUMP = "heating.circuits.1.circulation.pump";
    const HEATING_CIRCUITS_1_CIRCULATION_SCHEDULE = "heating.circuits.1.circulation.schedule";
    const HEATING_CIRCUITS_1_CIRCULATION = "heating.circuits.1.circulation";
    const HEATING_CIRCUITS_1_DHW_SCHEDULE = "heating.circuits.1.dhw.schedule";
    const HEATING_CIRCUITS_1_DHW = "heating.circuits.1.dhw";
    const HEATING_CIRCUITS_1_FROSTPROTECTION = "heating.circuits.1.frostprotection";
    const HEATING_CIRCUITS_1_HEATING_CURVE = "heating.circuits.1.heating.curve";
    const HEATING_CIRCUITS_1_HEATING_SCHEDULE = "heating.circuits.1.heating.schedule";
    const HEATING_CIRCUITS_1_HEATING = "heating.circuits.1.heating";
    const HEATING_CIRCUITS_1_OPERATING_MODES_ACTIVE = "heating.circuits.1.operating.modes.active";
    const HEATING_CIRCUITS_1_OPERATING_MODES_DHW = "heating.circuits.1.operating.modes.dhw";
    const HEATING_CIRCUITS_1_OPERATING_MODES_DHWANDHEATING = "heating.circuits.1.operating.modes.dhwAndHeating";
    const HEATING_CIRCUITS_1_OPERATING_MODES_FORCEDNORMAL = "heating.circuits.1.operating.modes.forcedNormal";
    const HEATING_CIRCUITS_1_OPERATING_MODES_FORCEDREDUCED = "heating.circuits.1.operating.modes.forcedReduced";
    const HEATING_CIRCUITS_1_OPERATING_MODES = "heating.circuits.1.operating.modes";
    const HEATING_CIRCUITS_1_OPERATING_MODES_STANDBY = "heating.circuits.1.operating.modes.standby";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_ACTIVE = "heating.circuits.1.operating.programs.active";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_COMFORT = "heating.circuits.1.operating.programs.comfort";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_ECO = "heating.circuits.1.operating.programs.eco";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_EXTERNAL = "heating.circuits.1.operating.programs.external";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_HOLIDAY = "heating.circuits.1.operating.programs.holiday";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_NORMAL = "heating.circuits.1.operating.programs.normal";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_REDUCED = "heating.circuits.1.operating.programs.reduced";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS = "heating.circuits.1.operating.programs";
    const HEATING_CIRCUITS_1_OPERATING_PROGRAMS_STANDBY = "heating.circuits.1.operating.programs.standby";
    const HEATING_CIRCUITS_1_OPERATING = "heating.circuits.1.operating";
    const HEATING_CIRCUITS_1_SENSORS = "heating.circuits.1.sensors";
    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE_ROOM = "heating.circuits.1.sensors.temperature.room";
    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE = "heating.circuits.1.sensors.temperature";
    const HEATING_CIRCUITS_1_SENSORS_TEMPERATURE_SUPPLY = "heating.circuits.1.sensors.temperature.supply";
    const HEATING_CIRCUITS_1 = "heating.circuits.1";
    const HEATING_CIRCUITS = "heating.circuits";
    const HEATING_CONTROLLER_SERIAL = "heating.controller.serial";
    const HEATING_DEVICE = "heating.device";
    const HEATING_DEVICE_TIME_OFFSET = "heating.device.time.offset";
    const HEATING_DEVICE_TIME = "heating.device.time";
    const HEATING_DHW_CHARGING_LEVEL = "heating.dhw.charging.level";
    const HEATING_DHW_CHARGING = "heating.dhw.charging";
    const HEATING_DHW_ONETIMECHARGE = "heating.dhw.oneTimeCharge";
    const HEATING_DHW_PUMPS_CIRCULATION = "heating.dhw.pumps.circulation";
    const HEATING_DHW_PUMPS_PRIMARY = "heating.dhw.pumps.primary";
    const HEATING_DHW_SCHEDULE = "heating.dhw.schedule";
    const HEATING_DHW_SENSORS = "heating.dhw.sensors";
    const HEATING_DHW_SENSORS_TEMPERATURE_HOTWATERSTORAGE = "heating.dhw.sensors.temperature.hotWaterStorage";
    const HEATING_DHW_SENSORS_TEMPERATURE_OUTLET = "heating.dhw.sensors.temperature.outlet";
    const HEATING_DHW = "heating.dhw";
    const HEATING_DHW_TEMPERATURE = "heating.dhw.temperature";
    const HEATING_ERRORS_ACTIVE = "heating.errors.active";
    const HEATING_ERRORS_HISTORY = "heating.errors.history";
    const HEATING_ERRORS = "heating.errors";
    const HEATING_GAS_CONSUMPTION_DHW = "heating.gas.consumption.dhw";
    const HEATING_GAS_CONSUMPTION_HEATING = "heating.gas.consumption.heating";
    const HEATING_SENSORS = "heating.sensors";
    const HEATING_SENSORS_TEMPERATURE_OUTSIDE = "heating.sensors.temperature.outside";
    const HEATING_SENSORS_TEMPERATURE = "heating.sensors.temperature";
    const HEATING_SERVICE = "heating.service";
    const HEATING_SERVICE_TIMEBASED = "heating.service.timeBased";
    const HEATING = "heating";
    const HEATING_POWER_CONSUMPTION = "heating.power.consumption";
    const HEATING_SOLAR_POWER_PRODUCTION = "heating.solar.power.production";
    const HEATING_SOLAR_SENSORS_TEMPERATURE_COLLECTOR = "heating.solar.sensors.temperature.collector"; 
}
