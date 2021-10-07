<?php
namespace BA\UserType\Model\ResourceModel;

use BA\UserType\Api\Data\ValueListItemInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ValueListItem extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(ValueListItemInterface::SCHEMA, ValueListItemInterface::ITEM_ID);
    }   
}