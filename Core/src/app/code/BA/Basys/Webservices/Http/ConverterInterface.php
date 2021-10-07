<?php
namespace BA\Basys\Webservices\Http;

interface ConverterInterface
{
    /**
     * Convert response to array
     * 
     * @param mixed $response 
     * @return array 
     */
    public function convert($response);
}