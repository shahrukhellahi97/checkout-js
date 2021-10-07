<?php
namespace BA\BasysCatalog\Pricing\Price;

use Magento\Catalog\Pricing\Price\CustomOptionPrice;

class CustomPrice extends CustomOptionPrice
{
    public function getCustomAmount($amount = null, $exclude = null, $context = [])
    {
        return 1000;
    }
}