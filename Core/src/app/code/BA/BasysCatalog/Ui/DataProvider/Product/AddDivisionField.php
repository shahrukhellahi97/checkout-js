<?php
namespace BA\BasysCatalog\Ui\DataProvider\Product;

use Magento\Ui\DataProvider\AddFieldToCollectionInterface;

class AddDivisionField implements AddFieldToCollectionInterface
{
    public function addField(\Magento\Framework\Data\Collection $collection, $field, $alias = null)
    {
        /** @var \Magento\Framework\DB\Select $select */
        $collection->getSelect()->joinLeft(
            ['p' => 'ba_basys_catalog_product_map'],
            'p.entity_id=e.entity_id',
            'p.basys_id'
        )
        ->joinLeft(
            ['d' => 'ba_basys_store_division'],
            'd.division_id=p.division_id',
            'd.name as basys_division'
        );
    }
}
