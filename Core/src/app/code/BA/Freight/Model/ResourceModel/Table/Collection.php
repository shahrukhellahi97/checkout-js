<?php
namespace BA\Freight\Model\ResourceModel\Table;

use BA\Freight\Model\Table;
use BA\Freight\Model\ResourceModel\Table as ResourceModelTable;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Table::class, ResourceModelTable::class);
    }
}