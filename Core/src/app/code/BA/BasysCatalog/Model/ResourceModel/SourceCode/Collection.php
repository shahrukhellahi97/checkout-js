<?php
namespace BA\BasysCatalog\Model\ResourceModel\SourceCode;

use BA\BasysCatalog\Model\ResourceModel\SourceCode as ResourceModelSourceCode;
use BA\BasysCatalog\Model\SourceCode;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(SourceCode::class, ResourceModelSourceCode::class);
    }
}