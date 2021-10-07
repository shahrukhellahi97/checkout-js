<?php
namespace BA\BasysCatalog\Model\ResourceModel\KeyGroup;

use BA\BasysCatalog\Model\KeyGroup;
use BA\BasysCatalog\Model\ResourceModel\KeyGroup as ResourceModelKeyGroup;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(KeyGroup::class, ResourceModelKeyGroup::class);
    }
}