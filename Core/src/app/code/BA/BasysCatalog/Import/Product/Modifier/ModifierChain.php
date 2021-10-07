<?php
namespace BA\BasysCatalog\Import\Product\Modifier;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class ModifierChain implements ModifierInterface
{
    /** @var \BA\BasysCatalog\Import\Product\Modifier\ModifierInterface[]|array */
    protected $modifiers;

    public function __construct(array $modifiers = [])
    {
        $this->modifiers = $modifiers;
    }

    public function apply(ProductInterface $product, BasysProductInterface $basysProduct = null): ProductInterface
    {
        /** @var \BA\BasysCatalog\Import\Product\Modifier\ModifierInterface $modifier */
        foreach ($this->modifiers as $modifier) {
            if ($modifier instanceof ModifierInterface) {
                $product = $modifier->apply($product, $basysProduct);
            }
        }

        return $product;
    }
}
