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

class ViessmannFeatureLocalProxy extends ViessmannFeatureAbstractProxy

{
    private $features;

    public function __construct($features, $viessmannOauthClient,$installationId,$gatewayId)
    {
        parent::__construct($viessmannOauthClient,$installationId,$gatewayId);
        $this->features = $this->getAllFeaturesInformation($features);
    }

    private function getAllFeaturesInformation($features): array
    {
        $classes = array();
        foreach ($features['data'] as $feature) {
            if ($feature["properties"] != NULL) {
                $entityBuilder=new EntityBuilder();
                $entityBuilder->addClass($feature['feature']);
                $entityBuilder->addProperties($feature['properties']);
                $classes[$feature['feature']] = $entityBuilder->build();
            }
        }
        return $classes;
    }

    public function getEntity($resources)
    {

        if (!empty($this->features[$resources])) {
            return $this->features[$resources];
        } else {
            return NULL;
        }

    }

    public function getRawJsonData($resources)
    {
        if(empty($resources)){
            return json_encode(array_keys($this->features));
        }
        $entity = $this->getEntity($resources);
        if ($entity) {
            return $entity->toJson();
        } else {
            return "{\"statusCode\":404,\"error\":\"Not Found\",\"message\":\"FEATURE_NOT_FOUND\"}";
        }
    }
}