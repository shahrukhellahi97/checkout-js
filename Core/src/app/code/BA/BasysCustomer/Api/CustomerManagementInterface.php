<?php
namespace BA\BasysCustomer\Api;

use Magento\Customer\Api\Data\CustomerInterface;

interface CustomerManagementInterface
{
    /**
     * Create customer contact
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param bool $async
     * @param array $additional
     * @return \BA\BasysCustomer\Model\CustomerContact|null
     */
    public function create(CustomerInterface $customer, $divisionId = null, $currency = null);

    /**
     * Create a customer contact asynchronously
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return void
     */
    public function createAsync(CustomerInterface $customer, $divisionId = null, $currency = null);

    /**
     *
     * @param string $email
     * @return int|null
     */
    public function getContactIdFromEmail(string $email);
}
