<?php
namespace BA\UserType\Model\ResourceModel;

use BA\UserType\Api\Data\ValueListInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ValueList extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(ValueListInterface::SCHEMA, ValueListInterface::LIST_ID);
    }
}