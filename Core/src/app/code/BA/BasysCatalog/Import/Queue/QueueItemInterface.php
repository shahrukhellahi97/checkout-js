<?php
namespace BA\BasysCatalog\Import\Queue;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;

interface QueueItemInterface
{
    /**
     * @return \BA\BasysCatalog\Api\Data\BasysProductInterface 
     */
    public function getBasysProduct(): BasysProductInterface;

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface 
     */
    public function getProduct(): ProductInterface;
}