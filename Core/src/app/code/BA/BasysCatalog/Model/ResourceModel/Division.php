<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\BasysCatalog\Api\Data\DivisionInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Division extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(DivisionInterface::SCHEMA_NAME, DivisionInterface::ENTITY_ID);
    }
}