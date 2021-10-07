<?php
namespace BA\BasysCatalog\Import\Product\Modifier\Action;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\Modifier\Action\Util\ColourRegistry;
use BA\BasysCatalog\Import\Product\Modifier\ModifierInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class SetColour implements ModifierInterface
{
    /**
     * @var \BA\BasysCatalog\Import\Product\Modifier\Action\Util\ColourRegistry
     */
    protected $colourRegistry;

    public function __construct(
        ColourRegistry $colourRegistry
    ) {
        $this->colourRegistry = $colourRegistry;
    }

    public function apply(ProductInterface $product, ?BasysProductInterface $basysProduct = null): ProductInterface
    {
        $colour = $this->colourRegistry->getColourFromName(
            $product->getName()
        );

        if ($colour == null) {
            $this->colourRegistry->getColourFromName(
                $product->getDescription()
            );
        }

        if ($colour) {
            $product->setCustomAttribute('color', $colour);
        }

        return $product;
    }
}