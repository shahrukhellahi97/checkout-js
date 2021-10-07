<?php
namespace BA\BasysCatalog\Model\ResourceModel\Lookup;

use BA\BasysCatalog\Model\Lookup;
use BA\BasysCatalog\Model\ResourceModel\Lookup as ResourceModelLookup;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Lookup::class, ResourceModelLookup::class);
    }
}