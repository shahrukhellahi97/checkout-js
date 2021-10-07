<?php
namespace BA\BasysCatalog\Import\Product\Save\Attribute;

use Magento\Catalog\Api\Data\ProductInterface;

class Varchar extends AbstractAttribute 
{
    /**
     * @var \Magento\Catalog\Model\Product\Url
     */
    protected $productUrl;

    public function __construct(
        AttributeIdResolver $attributeIdResolver,
        \Magento\Catalog\Model\Product\Url $productUrl
    ) {
        parent::__construct($attributeIdResolver);
        
        $this->productUrl = $productUrl;
    }

    public function getAttributes(): array
    {
        return [
            'name',
            'options_container',
            // 'msrp_display_actual_price_type',
            'is_returnable',
            'tax_category',
            'tax_type',
            'tax_code'
        ];
    }

    public function getDefaultAttributeValues(ProductInterface $product): array
    {
        return array_merge([
            'name' => $product->getName(),
            // 'msrp_display_actual_price_type' => 0,
            'is_returnable' => 2,
            'tax_category' => 'Standard',
            'tax_type' => 'VAT',
            'tax_code' => 'Standard',
            'options_container' => 'container2'
        ], $this->getAdditional($product));
    }

    private function getAdditional(ProductInterface $product): array
    {
        $additional = [];

        // if (!$product->getIsExistingProduct()) {
        //     $additional['url_key'] = $this->productUrl->formatUrlKey($product->getName());
        // }

        return $additional;
    }
    
    public function getTable()
    {
        return 'catalog_product_entity_varchar';   
    }
}