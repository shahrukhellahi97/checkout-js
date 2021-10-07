<?php
namespace BA\UserType\Model\ResourceModel;

use BA\UserType\Api\Data\ConfigInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Config extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(ConfigInterface::SCHEMA, ConfigInterface::CONFIG_ID);
    }
}