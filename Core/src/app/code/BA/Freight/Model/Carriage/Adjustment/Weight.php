<?php
namespace BA\Freight\Model\Carriage\Adjustment;

use BA\Freight\Model\Carriage\CarriageAdjustmentInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Store\Model\StoreManagerInterface;

class Weight implements CarriageAdjustmentInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    public function getAdjustmentValue(RateRequest $rateRequest, array $bands)
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();
        $displayCurrency = $store->getCurrentCurrencyCode();
        
        $returnValue = 0.00;

        foreach ($bands as $values) {
            $break = array_shift($values);

            foreach ($values as $currency => $value) {
                if (strtoupper($displayCurrency) === strtoupper($currency) && $rateRequest->getPackageWeight() <= $value) {
                    $returnValue = $value;
                }
            }
        }

        return $returnValue;
    }
}