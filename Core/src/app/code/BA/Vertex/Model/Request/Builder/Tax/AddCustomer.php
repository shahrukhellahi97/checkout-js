<?php
namespace BA\Vertex\Model\Request\Builder\Tax;

use BA\BasysCustomer\Api\CustomerManagementInterface;
use BA\Vertex\Model\Request\Builder\CalculateTaxRequestInterface;
use Magento\Quote\Model\Quote;

class AddCustomer implements CalculateTaxRequestInterface
{
    /**
     * @var \BA\BasysCustomer\Api\CustomerManagementInterface
     */
    protected $customerManagement;

    public function __construct(
        CustomerManagementInterface $customerManagement
    ) {
        $this->customerManagement = $customerManagement;
    }

    public function build(Quote $quote)
    {
        return [
            'CustomerContactID' => '5403255', // $this->customerManagement->getContactId($quote->getCustomer()),
        ];
    }
}