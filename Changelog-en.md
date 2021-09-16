Version 2.1.1
-------------- 
Deleted functions: 
    - getHeatingPrimaryCircuitTemperatureSupply()       -> replaced by getSupplyTemperature($circuitId = NULL)
    - getHeatingSecondaryCircuitTemperatureSupply()     -> replaced by getSupplyTemperature($circuitId = NULL)
    - getHeatingSecondaryCircuitTemperatureReturn()     -> replaced by getHeatingTemperatureReturn()
    - getHeatingBurnerCurrentPower()                    -> feature not available
    - getPumpsCirculationSchedule($circuitId = NULL)    -> feature not available

Added circuitId to functions:
    - getHeatingCompressorsStatistics($circuitId = NULL)
    - getHeatingBurnerStatistics($type = "hours", $circuitId = NULL)
    - getHeatingBurnerModulation($circuitId = NULL)
    - getDhwSchedule($circuitId = NULL)                 -> circuitId is optional. It depends on multiFamilyHouse is configured
    - setRawDhwSchedule($schedule, $circuitId = NULL)   -> circuitId is optional. It depends on multiFamilyHouse is configured
    - getDhwPumpsCirculationSchedule($circuitId = NULL) -> circuitId is optional. It depends on multiFamilyHouse is configured

New function:
    - setRawDhwPumpsCirculationSchedule($schedule, $circuitId = NULL)
    
Version 1.4.0
--------------
Switch to version 2 of Oauth Viessmann servuce. Many other small improvements. See release note https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.4.0

Version 1.3.4
--------------
Suppress dep on php 7.1 and fix  GetAvailableFeatures https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.4

Version 1.3.3
--------------

Added missing DateTime import.
https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.3

Version 1.3.2
--------------
Warning. This version requires php and php-curl 7.1 to support "?".
Added caching to reduced load is available here : https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.2
It's also possible to define installationId(3rd line) and gatewayId(4th line) in the credentials.properties.
To get those value please use the getGatewayId and getInstallationid method.
This would reduce the total of request to 3. Moreover authentication(2 request) seems to not be taken into account so it will result in only 1 request counting in the quota.

As mentionned, Viessmann as set 2 limit to their API:
* 120 calls for a time window of 10 minutes
* 1450 calls for a time window of 24 hours



Version 1.1.0 available !
-------------------------