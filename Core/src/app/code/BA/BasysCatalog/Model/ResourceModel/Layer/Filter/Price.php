<?php
namespace BA\BasysCatalog\Model\ResourceModel\Layer\Filter;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\App\ResourceConnection;

class Price
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Layer\Filter\ResourceConnection
     */
    protected $resource;

    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $BasysStoreManagement;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    public function __construct(
        ResourceConnection $resource,
        BasysStoreManagementInterface $BasysStoreManagement,
        Resolver $layerResolver
    ) {
        $this->resource = $resource;
        $this->BasysStoreManagement = $BasysStoreManagement;
        $this->layerResolver = $layerResolver;
    }

    public function getCurrentCategory()
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }

    public function getPrices()
    {
        $this->connection = $this->resource->getConnection();

        $select = $this->connection->select()
            ->from(['ent' => 'catalog_product_entity'])
            ->join(['map' => 'ba_basys_catalog_product_map'], 'ent.entity_id = map.entity_id')
            ->join(['cat' => 'catalog_category_product'], 'ent.entity_id = cat.product_id')
            ->join(
                ['productprice' => 'ba_basys_catalog_product_price'],
                'productprice.basys_id = map.basys_id',
                ['productprice.price']
            )
            ->where('productprice.catalog_id = ?', $this->BasysStoreManagement->getActiveCatalog()->getId())
            ->where('cat.category_id = ?', $this->getCurrentCategory()->getId())
            ->order('productprice.price ASC');

        $query = $this->connection->fetchAll($select);

        return $query;
    }
}
