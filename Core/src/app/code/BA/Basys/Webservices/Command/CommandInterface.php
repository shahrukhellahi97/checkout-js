<?php
namespace BA\Basys\Webservices\Command;

use BA\Basys\Webservices\Response\HandlerInterface;

interface CommandInterface
{
    /**
     * 
     * @param array $arguments 
     * @param array $additional 
     * @return mixed 
     */
    public function execute(array $arguments, array $additional = []);

    /**
     * Return command name
     * 
     * @return string 
     */
    public function getName(): string;
}