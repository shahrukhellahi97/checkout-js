<?php
namespace BA\Punchout\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class Json implements ClientInterface
{
    public function placeRequest(TransferInterface $transferObject)
    {
        return false;       
    }
}