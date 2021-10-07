<?php
namespace BA\BasysCatalog\Import\Product;

use Magento\Catalog\Api\Data\ProductInterface;

interface SaveHandlerInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return void
     */
    public function save(ProductInterface $product);
}
