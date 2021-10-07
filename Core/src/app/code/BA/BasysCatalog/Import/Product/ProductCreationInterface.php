<?php
namespace BA\BasysCatalog\Import\Product;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;

interface ProductCreationInterface
{
    /**
     * Create magento product from BASys product
     * 
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $product 
     * @return \Magento\Catalog\Api\Data\ProductInterface 
     */
    public function create(BasysProductInterface $product): ProductInterface;
}