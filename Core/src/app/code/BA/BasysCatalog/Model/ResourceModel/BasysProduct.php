<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\Basys\Exception\BasysException;
use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Psr\Log\LoggerInterface;

class BasysProduct extends AbstractDb
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;
    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        BasysStoreManagementInterface $basysStoreManagement,
        LoggerInterface $logger,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->basysStoreManagement = $basysStoreManagement;
        $this->logger = $logger;
    }

    public function _construct()
    {
        $this->_init(BasysProductInterface::SCHEMA, BasysProductInterface::PRODUCT_ID);
    }

    public function loadProductForCatalog(AbstractModel $model, $catalogId, $basysId)
    {
        $select = $this->getConnection()->select()
            ->from(
                ['prod' => $this->getMainTable()]
            )
            ->where(
                'prod.basys_id = ?',
                $basysId
            )
            ->where(
                'prod.catalog_id = ?',
                $catalogId
            );

        if ($data = $this->getConnection()->fetchRow($select)) {
            $model->setData($data);
        }

        return $this;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface|int $entity
     * @param int|null $catalogId
     * @throws \BA\Basys\Exception\BasysException
     * @throws \DomainException
     */
    public function getProductFromEntity(AbstractModel $model, $entity, $catalogId = null)
    {
        $entityId = ($entity instanceof ProductInterface) ?
            $entity->getId() :
            (int) $entity;

        $catalogId = $catalogId == null ?
            $this->basysStoreManagement->getActiveCatalog()->getId() :
            $catalogId;

        $select = $this->getConnection()->select()
            ->from(
                ['prod' => $this->getMainTable()]
            )
            ->joinRight(
                ['map' => $this->getTable('ba_basys_catalog_product_map')],
                'map.basys_id = prod.basys_id',
                []
            )
            ->where(
                'map.entity_id = ?',
                $entityId
            )
            ->where(
                'prod.catalog_id = ?',
                $catalogId,
            );

        $x = $select->__toString();
        
        if ($data = $this->getConnection()->fetchRow($select)) {
            return $model->setData($data);
        }

        throw new BasysException(__(sprintf("Unable to find entity => catalog mapping %s:%s", $entityId, $catalogId)));
    }
}
