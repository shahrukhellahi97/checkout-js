<?php
namespace BA\BasysCatalog\Import\Product\Type;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\ProductCreationInterface;
use Magento\Catalog\Api\Data\ProductInterface;

abstract class AbstractType implements ProductCreationInterface
{
    /**
     * @var \Magento\Catalog\Api\Data\ProductInterfaceFactory
     */
    protected $productFactory;

    public function __construct(
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory
    ) {
        $this->productFactory = $productFactory;
    }

    abstract public function create(BasysProductInterface $product): ProductInterface;

    protected function getPrice(BasysProductInterface $product): float
    {
        try {
            return $product->getPrice();
        } catch (\Exception $e) {
            return 0.00;
        }
    }
}
