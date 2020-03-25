<?php
/**
 * Created by IntelliJ IDEA.
 * User: clibois
 * Date: 17/03/20
 * Time: 18:06
 */

namespace Viessmann\test;

use DateTime;
use Viessmann\API\proxy\ViessmannFeatureProxy;
use Viessmann\API\ViessmannApiException;
use Viessmann\Oauth\ViessmannOauthClient;
use TomPHP\Siren\Entity;
class ViessmannFeatureRemoteMockProxy implements ViessmannFeatureProxy

{
    public function __construct($resourcesDir = __DIR__ . "/resources/features/heating/")
    {
        $this->resourcesDir = $resourcesDir;
    }

    public function getRawJsonData($resources): string
    {
        return file_get_contents($this->resourcesDir . $resources . ".json");

    }
    public function getEntity($resources)
    {

        $data = json_decode($this->getRawJsonData($resources), true);
        if (isset($data["statusCode"])) {
            if($data["statusCode"]=="429"){
                $epochtime=(int)($resources["extendedPayload"]["limitReset"]/1000);
                $dt = new DateTime("@$epochtime");
                $resetDate=$dt->format(DateTime::RSS);
                throw new ViessmannApiException("\n\t Unable to read installation basic information \n\t Reason: ". $data["message"]." Limit will be reset on ".$resetDate, 2);
            }else{
                throw new ViessmannApiException("Unable to get data for feature " . $resources . "\nReason: " . $data["message"], 1);
            }
        }

        return Entity::fromArray($data, true);

    }


    public function setData($feature, $action, $data)
    {
        throw new ViessmannApiException("Not implmented for test");
    }
}