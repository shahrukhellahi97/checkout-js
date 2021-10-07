<?php
namespace BA\BasysCustomer\Model\Request\Builder;

use Magento\Customer\Api\Data\CustomerInterface;

interface CustomerRequestInterface
{
    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer 
     * @return array 
     */
    public function build(CustomerInterface $customer): array;
}