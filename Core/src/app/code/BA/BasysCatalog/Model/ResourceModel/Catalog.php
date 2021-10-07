<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Catalog extends AbstractDb
{
    /**
     * @var \BA\BasysCatalog\Model\CatalogFactory
     */
    protected $catalogFactory;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \BA\BasysCatalog\Model\CatalogFactory $catalogFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->catalogFactory = $catalogFactory;
    }

    protected function _construct()
    {
        $this->_init(CatalogInterface::SCHEMA_NAME, CatalogInterface::ENTITY_ID);
    }

    public function getAll()
    {
        $select = $this->getConnection()
            ->select()
            ->from($this->getMainTable());

        $results = [];

        foreach ($this->getConnection()->fetchAll($select) as $result) {
            $results[] = $this->catalogFactory->create(['data' => $result]);
        }

        return $results;
    }

    /**
     *
     * @param mixed $divisionId
     * @return \BA\BasysCatalog\Model\Catalog[]|array
     * @throws \DomainException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Db_Select_Exception
     */
    public function getCatalogsForDivisionId($divisionId)
    {
        $select = $this->getConnection()
            ->select()
            ->from($this->getMainTable())
            ->where(
                'division_id = ?',
                $divisionId
            );

        $results = [];

        foreach ($this->getConnection()->fetchAll($select) as $result) {
            $results[] = $this->catalogFactory->create(['data' => $result]);
        }

        return $results;
    }
}
