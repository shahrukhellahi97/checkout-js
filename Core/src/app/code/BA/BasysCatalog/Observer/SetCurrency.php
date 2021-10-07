<?php
namespace BA\BasysCatalog\Observer;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SetCurrency implements ObserverInterface
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    public function __construct(
        BasysStoreManagementInterface $basysStoreManagement
    ) {
        $this->basysStoreManagement = $basysStoreManagement; 
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        
        $newCurrency = $this->basysStoreManagement->getActiveCatalog()->getCurrency();

        $quote->setBaseCurrencyCode($newCurrency);
        $quote->setStoreCurrencyCode($newCurrency);
    }
}
