<?php
namespace BA\Basys\Webservices\Command;

interface AsyncCommandMessageFactoryInterface
{
    /**
     * @param array $data 
     * @return \BA\Basys\Webservices\Command\AsyncCommandMessageInterface 
     */
    public function create(array $data = []): AsyncCommandMessageInterface;
}