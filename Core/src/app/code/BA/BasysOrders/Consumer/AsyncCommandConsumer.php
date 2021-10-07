<?php
namespace BA\BasysOrders\Consumer;

use BA\Basys\Webservices\Command\AsyncCommandMessageInterface;
use BA\Basys\Webservices\Consumer\AsyncCommandConsumerInterface;

class AsyncCommandConsumer implements AsyncCommandConsumerInterface
{
    public function process(AsyncCommandMessageInterface $message)
    {
        
    }
}