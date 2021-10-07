<?php
namespace BA\BasysCatalog\Import\Product\Save\Util;

use Magento\Catalog\Api\Data\ProductInterface;

class Product
{
    protected $ids = [];

    protected $products = [];

    public function stash(ProductInterface $product)
    {
        $this->products[$product->getSku()] = $product;
    }

    public function getProductId(string $sku)
    {
        if (isset($this->products[$sku])) {
            return $this->products[$sku]->getId();
        }

        return null;
    }
}