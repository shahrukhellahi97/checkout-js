<?php
namespace BA\Freight\Model\Carriage\Method;

use BA\Freight\Api\FreightCalculatorInterface;

abstract class AbstractMethod implements FreightCalculatorInterface
{
    /**
     * @var \BA\Freight\Helper\Data
     */
    protected $helper;

    /**
     * @var \BA\Freight\Model\Carriage\CarriageAdjustmentFactory
     */
    protected $carriageAdjustmentFactory;

    public function __construct(
        \BA\Freight\Helper\Data $helper,
        \BA\Freight\Model\Carriage\CarriageAdjustmentFactory $carriageAdjustmentFactory
    ) {
        $this->helper = $helper;
        $this->carriageAdjustmentFactory = $carriageAdjustmentFactory;
    }

    public function getAdjustmentModel($code)
    {
        return $this->carriageAdjustmentFactory->create(
            $this->helper->getCarriageModel($code)
        );
    }

    /**
     * 
     * @param string $code 
     * @return array
     */
    public function getBreaks($code)
    {
        $breaks = $this->helper->getBreaks($code);

        usort($breaks, function ($a, $b) {
            return ($a['value_break'] > $b['value_break']) ? -1 : 1;
        });

        return $breaks;
    }
}