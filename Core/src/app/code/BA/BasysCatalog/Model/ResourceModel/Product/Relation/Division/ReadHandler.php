<?php
namespace BA\BasysCatalog\Model\ResourceModel\Product\Relation\Division;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;

class ReadHandler implements ExtensionInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResource;

    public function __construct(ProductResource $productResource)
    {
        $this->productResource = $productResource;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface|\Magento\Framework\DataObject $entity 
     * @param array $arguments 
     * @return \Magento\Catalog\Api\Data\ProductInterface 
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $connection = $this->productResource->getConnection();
            $select = $connection->select()
                ->from(
                    ['map' => 'ba_basys_catalog_product_map']
                )
                ->joinInner(
                    ['prd' => $connection->getTableName('ba_basys_catalog_product')],
                    'prd.basys_id = map.basys_id',
                    [],
                )
                ->joinInner(
                    ['dv' => $connection->getTableName('ba_basys_store_division')],
                    'dv.division_id = prd.division_id',
                    [],
                )
                ->where(
                    'map.entity_id = ?', $entity->getId(),
                )
                ->columns(
                    'dv.name'
                )
                ->limit(1);

            $x = $connection->fetchRow($select);
            
            $entity->setData('basys_id', $x['name']);
        }

        return $entity;
    }
}