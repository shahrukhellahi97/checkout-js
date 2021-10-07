<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\BasysCatalog\Api\Data\LookupInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Lookup extends AbstractDb
{
    protected function _construct()
    {
        $this->_isPkAutoIncrement = false;
        $this->_init(LookupInterface::SCHEMA_NAME, LookupInterface::DIVISION_ID);
    }
}