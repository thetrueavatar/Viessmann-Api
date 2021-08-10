<?php
/**
 * Created by IntelliJ IDEA.
 * User: clibois
 * Date: 17/03/20
 * Time: 18:06
 */

namespace Viessmann\API\proxy\impl;

use TomPHP\Siren\Entity;
use TomPHP\Siren\EntityBuilder;
use Viessmann\API\ViessmannApiException;

class ViessmannFeatureLocalProxy extends ViessmannFeatureAbstractProxy

{
    private $features;

    public function __construct($features, $viessmannOauthClient,$installationId,$gatewayId)
    {
        parent::__construct($viessmannOauthClient,$installationId,$gatewayId);
        $this->features = $features;
    }

    public function getEntity($resources)
    {

        if (!empty($this->features[$resources])) {
            return $this->features[$resources];
        } else {
            throw new ViessmannApiException("Unable to get data for feature " . $resources . "\n Reason: No such Feature"); ;
        }

    }

    public function getRawJsonData($resources)
    {
        if(empty($resources)){
            $allfeatures=array();
            foreach (array_keys($this->features) as $feature){
                array_push($allfeatures,$this->getEntityJson($feature));
            }
            return json_encode($allfeatures);
        }
        else{
            return $this->features[$resources]->toJson();
        }

    }

    /**
     * @param $resources
     * @return string
     * @throws ViessmannApiException
     */
    public function getEntityJson($resources): string
    {
        $entity = $this->getEntity($resources);
        if ($entity) {
            return $entity->toJson();
        } else {
            return "{\"statusCode\":404,\"error\":\"Not Found\",\"message\":\"FEATURE_NOT_FOUND\"}";
        }
    }
}