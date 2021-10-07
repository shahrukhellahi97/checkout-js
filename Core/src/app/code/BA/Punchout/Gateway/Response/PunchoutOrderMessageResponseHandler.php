<?php
namespace BA\Punchout\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;

class PunchoutOrderMessageResponseHandler implements HandlerInterface
{
    public function handle(array $handlingSubject, array $response)
    {
        return;
    }
}