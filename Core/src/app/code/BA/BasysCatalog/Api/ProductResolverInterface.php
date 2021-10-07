<?php
namespace BA\BasysCatalog\Api;

use Magento\Catalog\Api\Data\ProductInterface;

interface ProductResolverInterface
{
    /**
     * Find the correct basys product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \BA\BasysCatalog\Api\Data\BasysProductInterface
     * @throws \BA\Basys\Exception\BasysException
     */
    public function get(ProductInterface $product);
}
