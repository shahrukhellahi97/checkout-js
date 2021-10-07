<?php
namespace BA\BasysCustomer\Observer;

use BA\BasysCustomer\Api\CustomerManagementInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerRegistrationSuccess implements ObserverInterface
{
    /**
     * @var \BA\BasysCustomer\Api\CustomerManagementInterface
     */
    protected $customerManagement;

    public function __construct(CustomerManagementInterface $customerManagement)
    {
        $this->customerManagement = $customerManagement;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
        $customer = $observer->getEvent()->getCustomer();

        $this->customerManagement->createAsync($customer);
    }
}
