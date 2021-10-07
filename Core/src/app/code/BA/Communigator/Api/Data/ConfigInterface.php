<?php
namespace BA\Communigator\Api\Data;

interface ConfigInterface
{
    /**
     * Get client ID
     * 
     * @return string
     */
    public function getClientId();

    /**
     * Get client secret
     * 
     * @return string
     */
    public function getClientSecret();

    /**
     * Get base64 encoded client:secret
     * 
     * @return string
     */
    public function getEncodedCredentials();

    /**
     * Get SSO Username
     * 
     * @return string
     */
    public function getUsername();

    /**
     * Get SSO Password
     *
     * @return string
     */
    public function getPassword();
}