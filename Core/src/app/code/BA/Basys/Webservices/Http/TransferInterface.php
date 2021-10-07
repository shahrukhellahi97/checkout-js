<?php
namespace BA\Basys\Webservices\Http;

interface TransferInterface
{
    /**
     * Get SOAP method (ex: LevelEnquiry)
     * 
     * @return string 
     */
    public function getMethod(): string;

    /**
     * @param string $password 
     * @return self
     */
    public function setMethod($method);

    /**
     * Get endpoint URI
     * 
     * @return string 
     */
    public function getUri(): string;

    /**
     * @param string $uri 
     * @return self
     */
    public function setUri($uri);

    /**
     * Get request body
     * 
     * @param array $arguments
     * @return array
     */
    public function getBody();

    /**
     * @param string $body 
     * @return self
     */
    public function setBody($body);

    /**
     * Get config array
     * 
     * @return null|array 
     */
    public function getConfig(): ?array;

    /**
     * @param array $config 
     * @return self
     */
    public function setConfig(array $config);
}