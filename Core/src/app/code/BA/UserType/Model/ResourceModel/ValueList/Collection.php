<?php
namespace BA\UserType\Model\ResourceModel\ValueList;

use BA\UserType\Model\ResourceModel\ValueList as ResourceModelValueList;
use BA\UserType\Model\ValueList;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ValueList::class, ResourceModelValueList::class);
    }
}