<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 31/08/18
 * Time: 12:43
 */

namespace Viessmann\API\Test;

use PHPUnit\Framework\TestCase;
use test\ViessmannMockClient;
use Viessmann\API\ViessmannAPI;

require __DIR__ . '/../../vendor/autoload.php';

class ViessmannApiTest extends TestCase
{

    private $viessmannApi;

    /**
     * ViessmannApiTest constructor.
     */
    public function __construct()
    {

        $viessmannApi = new ViessmannAPI([], new ViessmannMockClient());
    }

    /**
     * ViessmannOauthClientTest constructor.
     */
    public function testGasConsumption()
    {
        $this->viessmannApi->getHotWaterStorageTemperature();
    }
}