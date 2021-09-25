<?php
/**
 * Created by PhpStorm.
 * User: thetrueavatar
 * Date: 8/10/18
 * Time: 15:14
 */

namespace Viessmann\API\proxy;

use TomPHP\Siren\Entity;

/**
 * interface  ViessmannFeatureProxy
 * @package Viessmann\API\proxy\ViessmannFeatureProxy
 */
interface  ViessmannFeatureProxy {
    /**
     * getRawJsonData
     * @param $resources
     * @throws
     */
    public function getRawJsonData($resources);

    /**
     * getEntity
     * @param $resources
     * @throws
     */
    public function getEntity($resources);

    /**
     * setData
     * @param $feature
     * @param $action
     * @param $data
     * @throws
     */
    public function setData($feature, $action, $data);
}