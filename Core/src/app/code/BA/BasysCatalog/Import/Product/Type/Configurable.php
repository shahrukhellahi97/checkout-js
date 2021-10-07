<?php
namespace BA\BasysCatalog\Import\Product\Type;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class Configurable extends AbstractType
{
    public function create(BasysProductInterface $product): ProductInterface
    {
        /** @var \Magento\Catalog\Model\Product $newProduct */
        $newProduct = $this->productFactory->create();

        return $newProduct->setName($product->getReportTitle())
            ->setSku($product->getReportSku())
            ->setPrice(0.00)
            ->setDescription($product->getDescription())
            ->setAttributeSetId(4)
            ->setTaxClassId(0)
            ->setTypeId('configurable');
    }
}