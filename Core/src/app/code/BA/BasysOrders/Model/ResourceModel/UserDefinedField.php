<?php
namespace BA\BasysOrders\Model\ResourceModel;

use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class UserDefinedField extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(UserDefinedFieldInterface::SCHEMA, UserDefinedFieldInterface::SEQUENCE_ID);

        $this->_isPkAutoIncrement = false;
    }
}
