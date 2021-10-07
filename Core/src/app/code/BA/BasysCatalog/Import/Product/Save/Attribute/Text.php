<?php
namespace BA\BasysCatalog\Import\Product\Save\Attribute;

use Magento\Catalog\Api\Data\ProductInterface;

class Text extends AbstractAttribute
{
    public function getAttributes(): array
    {
        return [
            'description'
        ];
    }

    public function getDefaultAttributeValues(ProductInterface $product): array
    {
        $x = $product->getDescription();
        /** @var \Magento\Catalog\Model\Product $product */
        return [
            'description' => $product->getDescription(),
        ];
    }

    public function getTable()
    {
        return 'catalog_product_entity_text';
    }
}