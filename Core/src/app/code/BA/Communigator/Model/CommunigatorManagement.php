<?php
namespace BA\Communigator\Model;

use BA\Communigator\Api\CommunigatorManagementInterface;

class CommunigatorManagement implements CommunigatorManagementInterface
{
    public function subscribe(string $emailAddress): bool
    {
        return true;
    }
}