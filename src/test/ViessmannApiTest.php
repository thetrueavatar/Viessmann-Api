<?php

namespace Viessmann\API\Test;
require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Viessmann\API\ViessmannAPI;
use Viessmann\test\ViessmannMockClient;


class ViessmannApiTest extends TestCase
{

    private $viessmannApi;

    /**
     * ViessmannApiTest constructor.
     */
    public function setUp()
    {

        $this->viessmannApi = new ViessmannAPI(array(), new ViessmannMockClient());
    }

    public function testGetHeatingGasConsumption()
    {
        $heatingGasComsumption = $this->viessmannApi->getHeatingGasConsumption("day");
        print_r($heatingGasComsumption);
        $heatingGasComsumption = $this->viessmannApi->getHeatingGasConsumption("week");
        print_r($heatingGasComsumption);
        echo "\n";
        $heatingGasComsumption = $this->viessmannApi->getHeatingGasConsumption("month");
        print_r($heatingGasComsumption);
        echo "\n";
        $heatingGasComsumption = $this->viessmannApi->getHeatingGasConsumption("year");
        print_r($heatingGasComsumption);
        echo "\n";
        self::assertNotNull($heatingGasComsumption);

    }

    public function testGetDhwGasConsumption()
    {
        $heatingGasComsumption = $this->viessmannApi->getDhwGasConsumption("day");
        print_r($heatingGasComsumption);
        $heatingGasComsumption = $this->viessmannApi->getDhwGasConsumption("week");
        print_r($heatingGasComsumption);
        echo "\n";
        $heatingGasComsumption = $this->viessmannApi->getDhwGasConsumption("month");
        print_r($heatingGasComsumption);
        echo "\n";
        $heatingGasComsumption = $this->viessmannApi->getDhwGasConsumption("year");
        print_r($heatingGasComsumption);
        echo "\n";
        self::assertNotNull($heatingGasComsumption);

    }

    public function testGetHeatingBurnerStatistics()
    {
        $hoursStats = $this->viessmannApi->getHeatingBurnerStatistics("hours");
        $startsStats = $this->viessmannApi->getHeatingBurnerStatistics("starts");
        self::assertNotNull($hoursStats);
        self::assertNotNull($startsStats);
        echo "hours stats: " . $hoursStats;
        echo "\nstarts stats: " . $startsStats;
    }

    public function testGetSchedule()
    {
        $heatingSchedule = $this->viessmannApi->getHeatingSchedule();
        $circulationSchedule = $this->viessmannApi->getCirculationSchedule();
        $dhwSchedule = $this->viessmannApi->getDhwSchedule();
        echo "heating schedule ";
        print_r($heatingSchedule);
        echo "\n circulation schedule";
        print_r($circulationSchedule);
        echo "\n dhw schedule";
        print_r($dhwSchedule);
        self::assertNotNull($heatingSchedule);
        self::assertNotNull($circulationSchedule);
        self::assertNotNull($dhwSchedule);

    }

    public function testGetHeatingBurnerCurrentPower()
    {
        $heatingBurnerCurrentPower = $this->viessmannApi->getHeatingBurnerCurrentPower();
        echo "\nheating burner current power ";
        echo $heatingBurnerCurrentPower;
        self::assertNotNull($heatingBurnerCurrentPower);
    }

    public function testGetHeatingBurnerModulation()
    {
        $heatingBurnerModulation = $this->viessmannApi->getHeatingBurnerModulation();
        echo "\nheating burner modulation ";
        echo $heatingBurnerModulation;
        self::assertNotNull($heatingBurnerModulation);
    }

    public function testGetCirculationPumpStatus($circuitId = NULL)
    {
        $circulationPumpStatus = $this->viessmannApi->getCirculationPumpStatus();
        echo "\ngetCirculationPumpStatus  ";
        echo $circulationPumpStatus;
        self::assertNotNull($circulationPumpStatus);
    }

    public function testIsDhwCharging()
    {
        $isDhwCharging = $this->viessmannApi->isDhwCharging();
        echo "\nisDhwCharging  ";
        echo gettype($isDhwCharging);
        self::assertNotNull($isDhwCharging);
    }

    public function testGetDhwChargingLevel()
    {
        $dhwChargingLevel = $this->viessmannApi->getDhwChargingLevel();
        echo "\ngetDhwChargingLevel  ";
        echo $dhwChargingLevel;
        self::assertNotNull($dhwChargingLevel);
    }

    public function testIsOneTimeDhwCharge()
    {
        $isOneTimeDhwCharge = $this->viessmannApi->isOneTimeDhwCharge();
        echo "\nisOneTimeDhwCharge  ";
        echo $isOneTimeDhwCharge;
        self::assertNotNull($isOneTimeDhwCharge);
    }

    public function testGetDhwPumpsCirculation()
    {
        $dhwPumpsCirculation = $this->viessmannApi->getDhwPumpsCirculation();
        echo "\ndhwPumpsCirculation  ";
        echo $dhwPumpsCirculation;
        self::assertNotNull($dhwPumpsCirculation);
    }

    public function testGetDhwPumpsPrimary()
    {
        $dhwPumpsPrimary = $this->viessmannApi->getDhwPumpsPrimary();
        echo "\ndhwPumpsPrimary  ";
        echo $dhwPumpsPrimary;
        self::assertNotNull($dhwPumpsPrimary);
    }

    public function testGetDhwTemperature()
    {
        $dhwTemperature = $this->viessmannApi->getDhwTemperature();
        echo "\nDHW Temperature  ";
        echo $dhwTemperature;
        self::assertNotNull($dhwTemperature);
    }

}