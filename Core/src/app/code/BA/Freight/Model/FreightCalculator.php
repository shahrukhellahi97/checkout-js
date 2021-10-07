<?php
namespace BA\Freight\Model;

use BA\Freight\Api\FreightCalculatorInterface;
use BA\Freight\Helper\Data;
use BA\Freight\Model\Carriage\CarriageAdjustmentFactory;
use BA\Freight\Model\Carriage\CarriageMethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Quote\Model\Quote\Address\RateRequest;

class FreightCalculator implements FreightCalculatorInterface
{
    /**
     * @var \BA\Freight\Helper\Data
     */
    protected $helper;

    /**
     * @var \BA\Freight\Model\Carriage\CarriageMethodFactory
     */
    protected $carriageMethodFactory;

    public function __construct(
        Data $helper,
        CarriageMethodFactory $carriageMethodFactory
    ) {
        $this->carriageMethodFactory = $carriageMethodFactory;
        $this->helper = $helper;
    }

    public function calculate(AbstractCarrier $carrier, RateRequest $rateRequest)
    {
        $calculator = $this->carriageMethodFactory->create(
            $this->helper->getCarriageMethod($carrier->getCarrierCode())
        );

        if ($calculator) {
            return $calculator->calculate($carrier, $rateRequest);
        }

        return null;
    }
}
