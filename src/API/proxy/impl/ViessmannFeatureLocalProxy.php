<?php
/**
 * Created by IntelliJ IDEA.
 * User: clibois
 * Date: 17/03/20
 * Time: 18:06
 */

namespace Viessmann\API\proxy\impl;

use TomPHP\Siren\Entity;

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
        foreach ($features->getEntities() as $feature) {
            if ($feature->getProperties() != NULL) {
                $classes[$feature->getClasses()[0]] = $feature;
            }
        }
        return $classes;
    }

    public function getEntity($resources): ?Entity
    {
        if (!empty($this->features[$resources])) {
            return $this->features[$resources];
        } else {
            return NULL;
        }

    }

    public function getRawJsonData($resources)
    {
        $entity = $this->getEntity($resources);
        if ($entity) {
            return $entity->toJson();
        } else {
            return "{\"statusCode\":404,\"error\":\"Not Found\",\"message\":\"FEATURE_NOT_FOUND\"}";
        }
    }
}