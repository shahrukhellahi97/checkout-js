<?php
namespace BA\Communigator\Api;

use BA\Communigator\Api\Data\ConfigInterface;
use BA\Communigator\Api\Data\TokenInterface;

interface CommunigatorManagementInterface
{
    /**
     * @param string $emailAddress 
     * @return bool 
     */
    public function subscribe(string $emailAddress): bool;

    /**
     * Get bearer token from API
     * 
     * @param \BA\Communigator\Api\Data\ConfigInterface $config 
     * @return \BA\Communigator\Api\Data\TokenInterface
     * public function getToken(ConfigInterface $config): TokenInterface;
     */
}