<?php
namespace BA\UserType\Model;

use BA\UserType\Api\Data\UserTypeInterface;
use BA\UserType\Api\UserTypeManagementInterface;

class UserTypeManagement implements UserTypeManagementInterface
{
    public function getCurrentConfiguration() { }

    public function getEnabledCatalogIds()
    {
        // return [1497, 1498, 1499];
        // return [1494, 1495, 1496];
        return [];
    }
}