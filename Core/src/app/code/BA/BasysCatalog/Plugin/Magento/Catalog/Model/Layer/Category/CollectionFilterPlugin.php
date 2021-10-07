<?php
namespace BA\BasysCatalog\Plugin\Magento\Catalog\Model\Layer\Category;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;

class CollectionFilterPlugin
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagementInterface;

    public function __construct(BasysStoreManagementInterface $basysStoreManagementInterface)
    {
        $this->basysStoreManagementInterface = $basysStoreManagementInterface;
    }

    /**
     * Filter product collection
     *
     * @param \Magento\Catalog\Model\Layer\Category\CollectionFilter $subject
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Catalog\Model\Category $category
     * @return void
     */
    public function beforeFilter(
        \Magento\Catalog\Model\Layer\Category\CollectionFilter $subject,
        $collection,
        \Magento\Catalog\Model\Category $category
    ) {
        $catalogId = $this->basysStoreManagementInterface->getActiveCatalog()->getId();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection->joinField(
            'catalog_id',
            'ba_basys_catalog_product_index',
            'catalog_id',
            'entity_id=entity_id',
            ['catalog_id' => $catalogId],
            'left'
        );

        // Hack for virtuals
        $collection->getSelect()->where(
            new \Zend_Db_Expr(
                "IF(type_id = 'virtual', 1, catalog_id) IS NOT NULL"
            )
        );
    }
}
