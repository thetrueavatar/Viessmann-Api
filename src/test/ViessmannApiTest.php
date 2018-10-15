<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 31/08/18
 * Time: 12:43
 */

namespace Viessmann\API\Test;

use PHPUnit\Framework\TestCase;
use Viessmann\API\ViessmannAPI;
use Viessmann\test\ViessmannMockClient;

require __DIR__ . '/../../vendor/autoload.php';

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
}