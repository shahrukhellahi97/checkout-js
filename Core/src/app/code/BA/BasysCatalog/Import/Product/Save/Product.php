<?php
namespace BA\BasysCatalog\Import\Product\Save;

use Magento\Catalog\Api\Data\ProductInterface;

class Product implements TableInterface
{
    /**
     * @var \Magento\CatalogStaging\Model\ResourceModel\ProductSequence
     */
    protected $productSequence;

    /**
     * @var \BA\BasysCatalog\Import\Product\Save\Util\Product
     */
    protected $productUtil;

    public function __construct(
        \Magento\CatalogStaging\Model\ResourceModel\ProductSequence $productSequence,
        \BA\BasysCatalog\Import\Product\Save\Util\Product $productUtil
    ) {
        $this->productSequence = $productSequence;
        $this->productUtil = $productUtil;
    }

    public function isMultipleInserts(): bool
    {
        return false;
    }

    public function getTable()
    {
        return 'catalog_product_entity';
    }

    public function getRows(ProductInterface $product): array
    {

        if ($product->getId() == null) {
            $product->setId($this->productSequence->getNextValue());
            $product->setIsExistingProduct(true);
        }

        $this->productUtil->stash($product);
// if ($product->getTypeId() == null){
//     $sku = $product->getSku();
//     $x = 'xxx';
// }
        return [
            'type_id' => $product->getTypeId(),
            'attribute_set_id' => $product->getAttributeSetId() ?? 4,
            'sku' => $product->getSku(),
            'entity_id' => $product->getId(),
            'has_options' => 0,
            'required_options' => 0,
            'created_in' => 1,
            'row_id' => $product->getId()
        ];
    }

}