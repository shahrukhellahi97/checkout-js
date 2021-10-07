<?php
namespace BA\UserType\Model\ResourceModel\ValueListItem;

use BA\UserType\Model\ResourceModel\ValueListItem as ResourceModelValueListItem;
use BA\UserType\Model\ValueListItem;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ValueListItem::class, ResourceModelValueListItem::class);
    }
}