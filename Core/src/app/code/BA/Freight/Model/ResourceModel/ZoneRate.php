<?php
namespace BA\Freight\Model\ResourceModel;

use BA\Freight\Api\Data\TableInterface;
use BA\Freight\Api\Data\ZoneInterface;
use BA\Freight\Api\Data\ZoneRateInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ZoneRate extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(ZoneRateInterface::SCHEMA_NAME, ZoneRateInterface::TABLE_ID);
        $this->_isPkAutoIncrement = false;
    }
}