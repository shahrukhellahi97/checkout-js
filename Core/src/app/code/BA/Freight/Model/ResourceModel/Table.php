<?php
namespace BA\Freight\Model\ResourceModel;

use BA\Freight\Api\Data\TableInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Table extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(TableInterface::SCHEMA_NAME, TableInterface::TABLE_ID);
        $this->_isPkAutoIncrement = false;
    }
}