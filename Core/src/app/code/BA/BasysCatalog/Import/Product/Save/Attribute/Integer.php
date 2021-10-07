<?php
namespace BA\BasysCatalog\Import\Product\Save\Attribute;

use BA\BasysCatalog\Import\Product\Helper\Product;
use Magento\Catalog\Api\Data\ProductInterface;

class Integer extends AbstractAttribute
{
    /**
     * @var \BA\BasysCatalog\Import\Product\Helper\Product
     */
    protected $productHelper;

    public function __construct(
        AttributeIdResolver $attributeIdResolver,
        Product $productHelper
    ) {
        parent::__construct($attributeIdResolver);
        $this->productHelper = $productHelper;  
    }

    public function getAttributes(): array
    {
        return [
            'status',
            'visibility',
            'tax_class_id',
            'quantity_and_stock_status',
            'ba_is_basys_product'
        ];
    }

    public function getDefaultAttributeValues(ProductInterface $product): array
    {
        
        /** @var \Magento\Catalog\Model\Product $product */
        return [
            'status' => $product->getStatus() ?? 1,
            'visibility' => $product->getVisibility() ?? $this->getVisibility($product),
            'tax_class_id' => 2,
            'quantity_and_stock_status' => 2,
            'ba_is_basys_product' => 1
        ];
    }

    public function getTable()
    {
        return 'catalog_product_entity_int';   
    }

    public function getVisibility(ProductInterface $product)
    {
        return $this->productHelper->isGroupedProduct($product->getSku()) ? 1 : 4;
    }
}