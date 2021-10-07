<?php
namespace BA\BasysCatalog\Import\Product\Save;

use Magento\Catalog\Api\Data\ProductInterface;

interface TableInterface
{
    /**
     * @return string
     */
    public function getTable();

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product 
     * @return array 
     */
    public function getRows(ProductInterface $product): array;

    /**
     * @return bool 
     */
    public function isMultipleInserts(): bool;
}
