<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\ProductResolverInterface;
use BA\BasysCatalog\Model\BasysProductFactory;
use BA\BasysCatalog\Model\ResourceModel\BasysProduct as BasysProductResource;
use BA\BasysCatalog\Model\ResourceModel\BasysProduct\Collection as BasysProductCollection;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class ProductResolver implements ProductResolverInterface
{
    /**
     * @var \BA\BasysCatalog\Model\BasysProductFactory
     */
    protected $basysProductFactory;

    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\BasysProduct
     */
    protected $basysProductResource;

    /**
     * @var \BA\BasysCategory\Model\ResourceModel\CategoryLink\Collection
     */
    protected $basysProductCollection;

    public function __construct(
        BasysProductFactory $basysProductFactory,
        BasysStoreManagementInterface $basysStoreManagement,
        BasysProductResource $basysProductResource,
        BasysProductCollection $basysProductCollection
    ) {
        $this->basysProductFactory = $basysProductFactory;
        $this->basysStoreManagement = $basysStoreManagement;
        $this->basysProductCollection = $basysProductCollection;
        $this->basysProductResource = $basysProductResource;
    }

    public function get(ProductInterface $product)
    {
        /** @var \BA\BasysCatalog\Model\BasysProduct $basysProduct */
        $basysProduct = $this->basysProductFactory->create();

        return $this->basysProductResource->getProductFromEntity($basysProduct, $product);
    }
}
