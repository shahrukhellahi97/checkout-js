<?php
namespace BA\BasysCatalog\Pricing\Price;

use Magento\Catalog\Pricing\Price\BasePrice as PriceBasePrice;

class BasePrice extends PriceBasePrice
{
    public function getValue()
    {
        return 5;
    }
}