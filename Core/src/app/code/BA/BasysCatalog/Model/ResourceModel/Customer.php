<?php
namespace BA\BasysCatalog\Model\ResourceModel;

use BA\BasysCatalog\Api\Data\CustomerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Customer extends AbstractDb
{
    public function _construct()
    {
        $this->_init(CustomerInterface::SCHEMA_NAME, CustomerInterface::CUSTOMER_ID);
        $this->_isPkAutoIncrement = false;
    }
}