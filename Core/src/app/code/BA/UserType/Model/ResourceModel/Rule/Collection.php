<?php
namespace BA\UserType\Model\ResourceModel\Rule;

use BA\UserType\Model\ResourceModel\Rule as ResourceModelRule;
use BA\UserType\Model\Rule;

class Collection extends \Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init(Rule::class, ResourceModelRule::class);
    }   
}