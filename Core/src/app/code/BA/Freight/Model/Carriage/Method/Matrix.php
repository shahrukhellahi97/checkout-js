<?php
namespace BA\Freight\Model\Carriage\Method;

use BA\Freight\Api\FreightCalculatorInterface;
use BA\Freight\Helper\Data;
use BA\Freight\Model\Carriage\CarriageMethodFactory;
use BA\Freight\Model\ResourceModel\TableFactory as TableResourceFactory;
use BA\Freight\Model\TableFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Matrix extends AbstractMethod implements FreightCalculatorInterface
{
    /**
     * @var \BA\Freight\Model\ResourceModel\TableFactory
     */
    protected $tableResourceFactory;

    /**
     * @var \BA\Freight\Model\TableFactory
     */
    protected $tableFactory;

    /**
     * @var \BA\Freight\Model\ResourceModel\Table
     */
    protected $resource;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \BA\Freight\Helper\Data $helper,
        \BA\Freight\Model\Carriage\CarriageAdjustmentFactory $carriageAdjustmentFactory,
        TableResourceFactory $tableResourceFactory,
        TableFactory $tableFactory,
        PriceCurrencyInterface $priceCurrency
    ) {
        parent::__construct($helper, $carriageAdjustmentFactory);
        
        $this->tableResourceFactory = $tableResourceFactory;
        $this->tableFactory = $tableFactory;
        $this->priceCurrency = $priceCurrency;
    }

    public function calculate(AbstractCarrier $carrier, RateRequest $rateRequest)
    {
        $table = $this->tableFactory->create();
        $carrierCode = $carrier->getCarrierCode();

        $this->getResource()->load($table, $this->helper->getFreightTableId($carrierCode));

        $rate = $table->getRate($rateRequest->getDestCountryId(), $rateRequest->getPackageWeight());

        if ($rate) {
            $value = $this->priceCurrency->convertAndRound($rate->getValue());

            $breaks = $this->getBreaks($carrier->getCarrierCode());
            $adjustment = $this->getAdjustmentModel($carrier->getCarrierCode());
            $adjustedValue = $adjustment->getAdjustmentValue($rateRequest, $breaks);

            if ($adjustedValue > 0) {
                $value = $value * $adjustedValue;
            }
            
            return $value;
        }

        return null;
    }
    
    /**
     * @return \BA\Freight\Model\ResourceModel\Table
     */
    private function getResource()
    {
        if (!$this->resource) {
            $this->resource = $this->tableResourceFactory->create();
        }

        return $this->resource;
    }
}