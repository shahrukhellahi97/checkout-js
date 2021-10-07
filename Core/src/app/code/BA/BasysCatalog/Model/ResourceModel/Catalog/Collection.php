<?php
namespace BA\BasysCatalog\Model\ResourceModel\Catalog;

use BA\BasysCatalog\Model\Catalog;
use BA\BasysCatalog\Model\ResourceModel\Catalog as ResourceModelCatalog;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Catalog::class, ResourceModelCatalog::class);
    }
}