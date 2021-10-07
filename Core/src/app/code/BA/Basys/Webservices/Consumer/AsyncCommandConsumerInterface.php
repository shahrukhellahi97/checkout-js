<?php
namespace BA\Basys\Webservices\Consumer;

use BA\Basys\Webservices\Command\AsyncCommandMessageInterface;

interface AsyncCommandConsumerInterface
{
    public function process(AsyncCommandMessageInterface $message);
}