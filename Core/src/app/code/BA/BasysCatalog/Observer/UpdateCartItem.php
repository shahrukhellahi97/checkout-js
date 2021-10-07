<?php
namespace BA\BasysCatalog\Observer;

use Magento\Framework\Event\ObserverInterface;

class UpdateCartItem extends AbstractPricingObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->setCustomPrice(
            $observer->getCart()->getQuote()
        );
    }
}
