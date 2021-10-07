<?php
namespace BA\BasysOrders\Model\ResourceModel;

use BA\BasysOrders\Api\Data\UserDefinedFieldOptionInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class UserDefinedFieldOption extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(UserDefinedFieldOptionInterface::SCHEMA, UserDefinedFieldOptionInterface::SEQUENCE_ID);

        $this->_isPkAutoIncrement = false;
    }
}
