<?php
namespace BA\BasysOrders\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;

class InitializeResponseHandler implements HandlerInterface
{
    public function handle(array $handlingSubject, array $response)
    {
        $x = 'abc';
    }
}