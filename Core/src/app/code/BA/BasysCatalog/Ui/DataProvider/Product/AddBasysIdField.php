<?php
namespace BA\BasysCatalog\Ui\DataProvider\Product;

use Magento\Ui\DataProvider\AddFieldToCollectionInterface;

class AddBasysIdField implements AddFieldToCollectionInterface
{
    public function addField(\Magento\Framework\Data\Collection $collection, $field, $alias = null)
    {
        $collection->joinField(
            'basys_id',
            'ba_basys_catalog_product_map',
            'basys_id',
            'entity_id=entity_id',
            null,
            'left'
        );
    }
}