<?php
namespace BA\Freight\Model\Carriage\Method;

use BA\Freight\Api\FreightCalculatorInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;

class Fixed extends AbstractMethod implements FreightCalculatorInterface
{
    public function calculate(AbstractCarrier $carrier, RateRequest $rateRequest)
    {
        $breaks = $this->getBreaks($carrier->getCarrierCode());
        $adjustment = $this->getAdjustmentModel($carrier->getCarrierCode());

        $adjustedValue = $adjustment->getAdjustmentValue($rateRequest, $breaks);

        if ($adjustedValue > 0) {
            return $adjustedValue;
        }

        return null;
    }
}
