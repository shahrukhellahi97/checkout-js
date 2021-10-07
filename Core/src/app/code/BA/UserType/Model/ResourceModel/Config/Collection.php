<?php
namespace BA\UserType\Model\ResourceModel\Config;

use BA\UserType\Model\Config;
use BA\UserType\Model\ResourceModel\Config as ResourceModelConfig;

class Collection extends \Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init(Config::class, ResourceModelConfig::class);
    }   
}