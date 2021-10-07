<?php
namespace BA\Basys\Webservices\Command;

interface CommandPoolInterface
{
    /**
     * @param string $commandCode 
     * @return \BA\Basys\Webservices\Command\CommandInterface 
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function get($commandCode): CommandInterface;
}
