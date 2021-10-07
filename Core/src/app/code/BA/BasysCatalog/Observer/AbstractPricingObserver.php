<?php
namespace BA\BasysCatalog\Observer;

use BA\BasysCatalog\Helper\Pricing;
use Magento\Quote\Model\Quote;

abstract class AbstractPricingObserver
{
    /**
     * @var \BA\BasysCatalog\Helper\Pricing
     */
    protected $priceHelper;

    public function __construct(
        Pricing $priceHelper
    ) {
        $this->priceHelper = $priceHelper;
    }

    public function setCustomPrice($quote)
    {
        if ($quote instanceof Quote) {
            if ($items = $quote->getItems()) {
                foreach ($items as $item) {
                    if ($item->getProductOptions() == null) {
                        $price = $this->priceHelper->getPrice($item->getProduct(), $item->getQty());

                        if ($price != null) {
                            $item->setCustomPrice($price);
                            $item->setOriginalCustomPrice($price);
                            $item->getProduct()->setIsSuperMode(true);
                        }
                    } else {
                        $x = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
                    }
                }
            }
        }
    }
}