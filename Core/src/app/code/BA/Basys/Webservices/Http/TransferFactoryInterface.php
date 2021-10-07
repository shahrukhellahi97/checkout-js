<?php
namespace BA\Basys\Webservices\Http;

interface TransferFactoryInterface
{
    /**
     * Create transfer object
     * 
     * @param array $arguments 
     * @return \BA\Basys\Webservices\Http\TransferInterface
     */
    public function create(array $arguments): TransferInterface;
}