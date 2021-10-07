<?php
namespace BA\BasysCatalog\Model\ResourceModel\Product\Relation\Division;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class SaveHandler implements ExtensionInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $entity 
     * @param array $arguments 
     * @return \Magento\Catalog\Api\Data\ProductInterface 
     */
    public function execute($entity, $arguments = [])
    {
        return $entity;
    }
}