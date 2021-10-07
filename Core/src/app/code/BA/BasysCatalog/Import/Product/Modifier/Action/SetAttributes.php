<?php
namespace BA\BasysCatalog\Import\Product\Modifier\Action;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\Modifier\Action\Util\SizeRegistry;
use BA\BasysCatalog\Import\Product\Modifier\ModifierInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class SetAttributes implements ModifierInterface
{
    /**
     * @var \BA\BasysCatalog\Import\Product\Modifier\Action\Util\SizeRegistry
     */
    protected $sizeRegistry;

    public function __construct(
        SizeRegistry $sizeRegistry
    ) {
        $this->sizeRegistry = $sizeRegistry;
    }

    public function apply(ProductInterface $product, BasysProductInterface $basysProduct = null): ProductInterface
    {
        $product->setCustomAttribute('ba_is_basys_product', 1);

        // Set Variant name
        if ($basysProduct != null && $variant = $this->getProductVariant($basysProduct)) {
            $product->setCustomAttribute('ba_product_variant', $variant);

            // Set size as option
            if ($basysProduct != null && $sizeId = $this->sizeRegistry->getSizeFromVariant($variant)) {
                $product->setCustomAttribute('ba_product_size', $sizeId);
            }
        }

        return $product;
    }

    private function getProductVariant(BasysProductInterface $product): ?string
    {
        if (preg_match('/^[0-9]+$/i', $product->getSku())) {
            // Scania rubbish
        } else {
            // Nothing fancy here.
            return str_replace($product->getReportSku(), '', $product->getSku());
        }

        return null;
    }
}
