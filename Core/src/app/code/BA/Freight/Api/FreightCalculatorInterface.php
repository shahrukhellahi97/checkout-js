<?php
namespace BA\Freight\Api;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;

interface FreightCalculatorInterface
{
    /**
     * Calculate shipping total from rate request
     *
     * @param \Magento\Shipping\Model\Carrier\AbstractCarrier $carrier
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $rateRequest
     * @return float
     */
    public function calculate(AbstractCarrier $carrier, RateRequest $rateRequest);
}
