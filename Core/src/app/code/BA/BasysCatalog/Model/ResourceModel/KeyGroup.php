<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\BasysCatalog\Api\Data\KeyGroupInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class KeyGroup extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(KeyGroupInterface::SCHEMA_NAME, KeyGroupInterface::ENTITY_ID);
    }
}