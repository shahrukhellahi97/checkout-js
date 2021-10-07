<?php
namespace BA\BasysCatalog\Import\Product\Type;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class Simple extends AbstractType
{
    public function create(BasysProductInterface $product): ProductInterface
    {
        /** @var \Magento\Catalog\Model\Product $newProduct */
        $newProduct = $this->productFactory->create();

        return $newProduct->setName($product->getTitle())
            ->setSku($product->getSku())
            ->setPrice($this->getPrice($product))
            ->setDescription($product->getDescription())
            ->setAttributeSetId(4)
            ->setStatus(1)
            ->setWeight($product->getWeight() / 1000)
            ->setTaxClassId(2)
            ->setTypeId('simple');
    }
}