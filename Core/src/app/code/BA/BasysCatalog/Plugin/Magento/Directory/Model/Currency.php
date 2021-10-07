<?php
namespace BA\BasysCatalog\Plugin\Magento\Directory\Model;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Directory\Model\Currency as CurrencyModel;
use Magento\Framework\App\RequestInterface;

class Currency
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $BasysStoreManagement;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $cartHelper;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    public function __construct(
        BasysStoreManagementInterface $BasysStoreManagement,
        \Magento\Checkout\Helper\Cart $cartHelper
    ) {
        $this->BasysStoreManagement = $BasysStoreManagement;
        $this->cartHelper = $cartHelper;
       // $this->request = $request;
    }

    public function aroundGetConfigAllowCurrencies(
        CurrencyModel $subject,
        callable $proceed
    ) {
        // $quote = $this->cartHelper->getQuote();
    

        // $currencies = $this->BasysStoreManagement->getAvailableCurrencies();

        // if (count($currencies) >= 1) {
        //     return $currencies;
        // }
        
        return $proceed();
    }
}
