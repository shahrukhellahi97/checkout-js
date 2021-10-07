<?php
namespace BA\Basys\Webservices\Http;

interface ClientInterface
{
    /**
     * @param \BA\Basys\Webservices\Http\TransferInterface $transfer
     * 
     * @return mixed
     */
    public function execute(TransferInterface $transfer);
}