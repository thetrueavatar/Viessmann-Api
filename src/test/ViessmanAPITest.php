<?php
/**
 * Created by PhpStorm.
 * User: clibois
 * Date: 31/08/18
 * Time: 17:38
 */

namespace Viessman\API\Test;
use Viessman\API\ViessmanAPI;
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

use PHPUnit\Framework\TestCase;

class ViessmanAPITest extends TestCase
{
    public function testAll()
    {
        $viessmanApi=new ViessmanAPI();
        echo $viessmanApi->getInstallationId();
        echo $viessmanApi->getGatewayId();
        echo $viessmanApi->getFeatures();
    }
}
