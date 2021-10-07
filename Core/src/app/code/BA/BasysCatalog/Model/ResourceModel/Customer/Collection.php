<?php
namespace BA\BasysCatalog\Model\ResourceModel\Customer;

use BA\BasysCatalog\Model\Customer;
use BA\BasysCatalog\Model\ResourceModel\Customer as ResourceModelCustomer;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Customer::class, ResourceModelCustomer::class);
    }
}