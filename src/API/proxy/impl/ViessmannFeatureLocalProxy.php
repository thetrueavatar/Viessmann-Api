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

    public function __construct($features,$viessmannOauthClient)
    {
        parent::__construct($viessmannOauthClient);
        $this->features=$this->getAllFeaturesInformation($features);
    }
    private function getAllFeaturesInformation($features): array
    {
        $classes=array();
        foreach ($features->getEntities() as $feature) {
            if ($feature->getProperties()!=NULL){
                $classes[$feature->getClasses()[0]]= $feature;
            }
        }
        return $classes;
    }

    public function getEntity($resources): Entity
    {
        return $this->features[$resources];
    }
}