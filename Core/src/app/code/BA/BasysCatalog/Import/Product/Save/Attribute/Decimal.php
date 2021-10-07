<?php
namespace BA\BasysCatalog\Import\Product\Save\Attribute;

use Magento\Catalog\Api\Data\ProductInterface;

class Decimal extends AbstractAttribute
{
    public function getAttributes(): array
    {
        return [
            'price',
            'weight'
        ];
    }

    public function getDefaultAttributeValues(ProductInterface $product): array
    {
        return [
            'price' => $product->getPrice() ?? 0.00,
            'weight' => $product->getWeight() ?? 0.00
        ];
    }

    public function getTable()
    {
        return 'catalog_product_entity_decimal';
    }
}