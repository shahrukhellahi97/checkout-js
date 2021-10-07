<?php
namespace BA\BasysCatalog\Import\Product\Type;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\ProductMetadataProviderInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class Grouped extends AbstractType
{
    /**
     * @var \BA\BasysCatalog\Import\Product\ProductMetadataProviderInterface
     */
    protected $productMetadata;

    public function __construct(
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
        ProductMetadataProviderInterface $productMetadata
    ) {
        parent::__construct($productFactory);

        $this->productMetadata = $productMetadata;
    }

    public function create(BasysProductInterface $product): ProductInterface
    {
        /** @var \Magento\Catalog\Model\Product $newProduct */
        $newProduct = $this->productFactory->create();

        return $newProduct->setName($product->getReportTitle())
            ->setSku($this->productMetadata->getSku($product))
            ->setPrice(0)
            ->setDescription($product->getDescription())
            ->setAttributeSetId(4)
            ->setWeight(0)
            ->setTaxClassId(0)
            ->setTypeId('grouped');
    }
}
