<?php
namespace BA\BasysCatalog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SetBasysPrice extends AbstractPricingObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote\Item $item */
        $items = $observer->getEvent()->getData('quote_item');

        $this->setCustomPrice(
            $items->getQuote()
        );
    }
}
