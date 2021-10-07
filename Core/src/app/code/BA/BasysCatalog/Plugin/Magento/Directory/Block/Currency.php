<?php
namespace BA\BasysCatalog\Plugin\Magento\Directory\Block;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Directory\Block\Currency as CurrencyBlock;

class Currency
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $BasysStoreManagement;

    /**
     * @var array
     */
    protected $currencies;

    public function __construct(BasysStoreManagementInterface $BasysStoreManagement)
    {
        $this->BasysStoreManagement = $BasysStoreManagement;
    }

    public function aroundGetCurrencies(
        CurrencyBlock $subject,
        callable $proceed
    ) {
        // $currencies = $this->BasysStoreManagement->getAvailableCurrencies();

        // if (count($currencies) >= 1) {
        //     $subject->setData('currencies', $currencies);
        // }

        return $proceed();
    }
}
