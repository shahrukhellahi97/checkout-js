<?php
namespace BA\Freight\Model\Carriage;

use Magento\Quote\Model\Quote\Address\RateRequest;

interface CarriageAdjustmentInterface
{
    /**
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $rateRequest
     * @param array $bands
     * @return float
     */
    public function getAdjustmentValue(RateRequest $rateRequest, array $bands);
}
