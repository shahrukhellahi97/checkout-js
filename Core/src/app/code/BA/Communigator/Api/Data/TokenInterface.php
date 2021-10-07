<?php
namespace BA\Communigator\Api\Data;

interface TokenInterface
{
    /**
     * Get bearer token
     * 
     * @return string
     */
    public function getToken();

    /**
     * Get expiration in seconds
     * 
     * @return int
     */
    public function getExpiry();

    /**
     * @return \DateTime
     */
    public function getExpiryDate();
}