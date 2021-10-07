<?php
namespace BA\Basys\Webservices\Request;

interface RequestBuilderInterface
{
    /**
     * Build request body
     * 
     * @param array $arguments 
     * @return array 
     */
    public function build(array $arguments);
}