<?php
namespace BA\BasysCatalog\Import\Product\Save\Attribute;

use BA\BasysCatalog\Import\Product\Save\TableInterface;
use Magento\Catalog\Api\Data\ProductInterface;

abstract class AbstractAttribute implements TableInterface
{
    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var \BA\BasysCatalog\Import\Product\Save\Attribute\AttributeIdResolver
     */
    protected $attributeIdResolver;

    public function __construct(
        AttributeIdResolver $attributeIdResolver
    ) {
        $this->attributeIdResolver = $attributeIdResolver;
    }

    abstract public function getAttributes(): array;

    abstract public function getDefaultAttributeValues(ProductInterface $product): array;

    public function isMultipleInserts(): bool
    {
        return false;
    }

    public function getRows(ProductInterface $product): array
    {
        $attributes = $this->attributeIdResolver->getAttributeId(
            array_merge(
                $this->getAttributes(),
                array_keys($this->getDefaultValues($product))
            )
        );

        $result = [];

        foreach ($attributes as $code => $id) {
            /** @var \Magento\Catalog\Model\Product $product */
            $setValue = $product->getCustomAttribute($code) ?? $product->getData($code);

            if ($value = $this->getDefaultValueForCode($code, $product)) {
                if ($value instanceof \Magento\Framework\Api\AttributeValue) {
                    $setValue = $value->getValue();
                } else {
                    $setValue = $value;
                }
            }

            $result[] = [
                'store_id' => 0,
                'attribute_id' => $id,
                'value' => $setValue,
                'row_id' => $product->getId(),
            ];
        }

        return $result;
    }

    protected function getDefaultValues(ProductInterface $product)
    {
        if (!$this->defaults) {
            $this->defaults = $this->getDefaultAttributeValues($product);
        }

        return $this->defaults;
    }

    protected function getDefaultValueForCode($code, ProductInterface $product)
    {
        $defaults = $this->getDefaultValues($product);

        if (isset($defaults[$code])) {
            return $defaults[$code];
        }

        return null;
    }
}
