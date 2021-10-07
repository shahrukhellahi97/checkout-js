<?php
namespace BA\UserType\Api;

use BA\UserType\Api\Data\ConfigInterface;
use BA\UserType\Api\Data\UserTypeInterface;

interface UserTypeManagementInterface
{
    /**
     * @return \BA\UserType\Api\Data\ConfigInterface 
     */
    public function getCurrentConfiguration();

    /**
     * @return int[]|null
     */
    public function getEnabledCatalogIds();
}