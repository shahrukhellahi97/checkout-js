<?php
namespace BA\BasysCatalog\Import\Product;

use Magento\Catalog\Api\Data\ProductInterface;

interface BatchSaveInterface
{
    public function add(ProductInterface $product);

    public function save();
}