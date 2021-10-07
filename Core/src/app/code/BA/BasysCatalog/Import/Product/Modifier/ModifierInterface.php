<?php
namespace BA\BasysCatalog\Import\Product\Modifier;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;

interface ModifierInterface
{
    /**
     * Apply a modification to a magento product (ex: set website id)
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface|null $basysProduct
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function apply(ProductInterface $product, BasysProductInterface $basysProduct = null): ProductInterface;
}
