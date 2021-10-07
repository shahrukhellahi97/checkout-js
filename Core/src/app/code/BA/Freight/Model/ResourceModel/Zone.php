<?php
namespace BA\Freight\Model\ResourceModel;

use BA\Freight\Api\Data\ZoneInterface;
use BA\Freight\Api\Data\ZoneRateInterface;
use BA\Freight\Model\ZoneRateFactory;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Zend_Db;
use Zend_Db_Expr;

class Zone extends AbstractDb
{
    /**
     * @var \BA\Freight\Model\ZoneRateFactory
     */
    protected $zoneRateFactory;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        ZoneRateFactory $zoneRateFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->zoneRateFactory = $zoneRateFactory;
    }

    protected function _construct()
    {
        $this->_init(ZoneInterface::SCHEMA_NAME, ZoneInterface::TABLE_ID);
        $this->_isPkAutoIncrement = false;
    }

    public function getRateForWeight($tableId, $countryId, $weight)
    {
        $select = $this->getConnection()->select()
            ->from(
                ['p' => $this->getMainTable()],
                'r.*'
            )
            ->join(
                ['r' => $this->getTable(ZoneRateInterface::SCHEMA_NAME)],
                'r.code_id = p.code_id AND r.table_id = p.table_id',
                []
            )
            ->where(
                'p.table_id = ?',
                $tableId,
            )
            ->where(
                'p.country_id = ?',
                $countryId
            )
            ->where(
                'r.weight >= LEAST((SELECT MAX(weight) FROM ba_freight_table_zone_rate WHERE table_id = r.table_id AND code_id = r.code_id), ?)',
                $weight
            )
            ->order('r.weight ASC')
            ->limit(1);

        $x = $select->__toString();

        if ($data = $this->getConnection()->fetchRow($select)) {
            /** @var \BA\Freight\Model\ZoneRate $rate */
            $rate = $this->zoneRateFactory->create();

            return $rate->setData($data);
        }

        return null;
    }
}
