<?php
/**
 * Created by IntelliJ IDEA.
 * User: clibois
 * Date: 17/03/20
 * Time: 18:06
 */

namespace Viessmann\API\proxy\impl;

use Viessmann\Oauth\ViessmannOauthClient;
use TomPHP\Siren\Entity;
class ViessmannFeatureRemoteProxy extends ViessmannFeatureAbstractProxy

{
    public function __construct($viessmannClient)
    {
        parent::__construct($viessmannClient);
    }

    private function getRawJsonData($resources): string
    {
        try {
            return $this->viessmannClient->readData($resources);
        } catch (TokenResponseException $e) {
            throw new ViessmannApiException("Unable to get data for feature" . $resources . "\n Reason: " . $e->getMessage(), 1, $e);
        }
    }
    public function getEntity($resources): Entity
    {

        $data = json_decode($this->getRawJsonData($resources), true);
        if (isset($data["statusCode"])) {
            throw new ViessmannApiException("Unable to get data for feature " . $resources . "\nReason: " . $data["message"], 1);
        }

        return Entity::fromArray($data, true);

    }


}