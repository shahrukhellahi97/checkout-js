<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\BasysCatalog\Api\Data\LevelInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Level extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(LevelInterface::SCHEMA_NAME, LevelInterface::BASYS_ID);
        $this->_isPkAutoIncrement = false;
    }
}