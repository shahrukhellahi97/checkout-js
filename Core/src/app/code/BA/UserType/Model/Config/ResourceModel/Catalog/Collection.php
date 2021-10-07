<?php
namespace BA\UserType\Model\Config\ResourceModel\Catalog;

use BA\UserType\Model\Config\Catalog;
use BA\UserType\Model\Config\ResourceModel\Catalog as ResourceModelCatalog;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Catalog::class, ResourceModelCatalog::class);
    }
}