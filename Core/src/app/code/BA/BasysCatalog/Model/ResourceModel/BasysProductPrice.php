<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\Basys\Exception\BasysException;
use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\Data\BasysProductPriceInterface;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class BasysProductPrice extends AbstractDb
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $BasysStoreManagement;

    /**
     * @var \BA\BasysCatalog\Model\BasysProductPriceFactory
     */
    protected $priceFactory;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        BasysStoreManagementInterface $BasysStoreManagement,
        \BA\BasysCatalog\Model\BasysProductPriceFactory $priceFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->priceFactory = $priceFactory;
        $this->BasysStoreManagement = $BasysStoreManagement;
    }

    public function _construct()
    {
        $this->_isPkAutoIncrement = false;
        $this->_init(BasysProductPriceInterface::SCHEMA, ''); // , BasysProductInterface::PRODUCT_ID);
    }

    /**
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $basysProduct
     * @return \BA\BasysCatalog\Model\BasysProductPrice[]|array
     * @throws \DomainException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Db_Select_Exception
     */
    public function getBasysPrices(BasysProductInterface $basysProduct)
    {
        $select = $this->getConnection()->select()
            ->from(
                ['p' => $this->getMainTable()]
            )
            ->where(
                'p.catalog_id = ?',
                $basysProduct->getCatalogId()
            )
            ->where(
                'p.basys_id = ?',
                $basysProduct->getBasysId(),
            )
            ->order('p.break DESC');

        $result = [];

        foreach ($this->getConnection()->fetchAll($select) as $row) {
            /** @var \BA\BasysCatalog\Model\BasysProductPrice $price */
            $price = $this->priceFactory->create();

            $result[] = $price->setData($row);
        }

        return $result;
    }

    /**
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $basysProduct
     * @param int $quantity
     * @return \BA\BasysCatalog\Model\BasysProductPrice
     * @throws \DomainException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Db_Select_Exception
     */
    public function getBasysPriceForQuantity(BasysProductInterface $basysProduct, $quantity = 0)
    {
        /** @var \BA\BasysCatalog\Model\BasysProduct $basysProduct */
        $tableName = $basysProduct->getData('queued') != null ?
            $this->getMainTable() . '_queue' :
            $this->getMainTable();

        $select = $this->getConnection()->select()
            ->from(
                ['p' => $tableName]
            )
            ->where(
                'p.catalog_id = ?',
                $basysProduct->getCatalogId()
            )
            ->where(
                'p.basys_id = ?',
                $basysProduct->getBasysId(),
            )->where(
                'p.break <= ?',
                $quantity
            )
            ->order('p.break DESC')
            ->limit(1);

        $x = $select->__toString();

        if ($data = $this->getConnection()->fetchRow($select)) {
            /** @var \BA\BasysCatalog\Model\BasysProductPrice $price */
            $price = $this->priceFactory->create();

            return $price->setData($data);
        }

        throw new BasysException(__(sprintf("Unable to find price break for %s:%s", $basysProduct->getSku(), $quantity)));
    }
}
