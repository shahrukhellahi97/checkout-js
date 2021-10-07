<?php
namespace BA\BasysCatalog\Model\ResourceModel\Division;

use BA\BasysCatalog\Model\Division;
use BA\BasysCatalog\Model\ResourceModel\Division as ResourceModelDivision;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Division::class, ResourceModelDivision::class);
    }
}