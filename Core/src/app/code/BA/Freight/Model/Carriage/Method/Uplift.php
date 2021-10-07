<?php
namespace BA\Freight\Model\Carriage\Method;

use BA\Freight\Api\FreightCalculatorInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;

class Uplift implements FreightCalculatorInterface
{
    public function calculate(AbstractCarrier $carrier, RateRequest $rateRequest)
    {

    }
}