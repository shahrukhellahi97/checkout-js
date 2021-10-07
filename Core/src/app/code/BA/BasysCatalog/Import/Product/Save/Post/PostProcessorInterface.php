<?php
namespace BA\BasysCatalog\Import\Product\Save\Post;

interface PostProcessorInterface
{
    /**
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $adapter 
     * @param \Magento\Catalog\Api\Data\ProductInterface[] $products 
     * @return void 
     */
    public function process(
        \Magento\Framework\DB\Adapter\AdapterInterface $adapter,
        array $products
    );
}