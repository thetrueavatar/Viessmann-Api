<?php
/**
 * Created by IntelliJ IDEA.
 * User: clibois
 * Date: 17/03/20
 * Time: 18:06
 */

namespace Viessmann\API\proxy\impl;

use DateTime;
use Viessmann\API\ViessmannApiException;
use Viessmann\Oauth\ViessmannOauthClient;
use TomPHP\Siren\Entity;
class ViessmannFeatureRemoteProxy extends ViessmannFeatureAbstractProxy

{
    public function __construct($viessmannClient,$installationId,$gatewayId)
    {
        parent::__construct($viessmannClient,$installationId,$gatewayId);

    }

    public function getRawJsonData($resources): string
    {
        try {
            return $this->viessmannClient->readData($this->featureHeatingBaseUrl . "/" . $resources);
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("Unable to get data for feature" . $resources . "\n Reason: " . $e->getMessage(), 1, $e);
        }
    }
    public function getEntity($resources)
    {

        $data = json_decode($this->getRawJsonData($resources), true);
        if (isset($data["statusCode"])) {
            if($data["statusCode"]=="429"){
                $epochtime=(int)($data["extendedPayload"]["limitReset"]/1000);
                $dt = new DateTime("@$epochtime");
                $resetDate=$dt->format(DateTime::RSS);
                throw new ViessmannApiException("\n\t Unable to read installation basic information \n\t Reason: ". $data["message"]." Limit will be reset on ".$resetDate, 2);
            }else{
                throw new ViessmannApiException("Unable to get data for feature " . $resources . "\nReason: " . $data["message"], 1);
            }
        }

        return Entity::fromArray($data, true);

    }


}