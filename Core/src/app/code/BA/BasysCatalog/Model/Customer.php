<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\CustomerInterface;
use BA\BasysCatalog\Model\ResourceModel\Customer as ResourceModelCustomer;
use Magento\Framework\Model\AbstractModel;

class Customer extends AbstractModel implements CustomerInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelCustomer::class);
    }


    public function getCustomerId()
    {
        return $this->getData(CustomerInterface::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(CustomerInterface::CUSTOMER_ID, $customerId);
    }

    public function getDivisionId()
    {
        return $this->getData(CustomerInterface::DIVISION_ID);
    }

    public function setDivisionId($divisionId)
    {
        return $this->setData(CustomerInterface::DIVISION_ID, $divisionId);
    }

    public function getSourceCodeId()
    {
        return $this->getData(CustomerInterface::SOURCE_CODE_ID);
    }

    public function setSourceCodeId($sourceCodeId)
    {
        return $this->setData(CustomerInterface::SOURCE_CODE_ID, $sourceCodeId);
    }

    public function getName()
    {
        return $this->getData(CustomerInterface::NAME);
    }

    public function setName($name)
    {
        return $this->setData(CustomerInterface::NAME, $name);
    }

    public function getCustomerContactId()
    {
        return $this->getData(CustomerInterface::CUSTOMER_CONTACT_ID);
    }

    public function setCustomerContactId($contactId)
    {
        return $this->setData(CustomerInterface::CUSTOMER_CONTACT_ID, $contactId);
    }

    public function getSalesPersonId()
    {
        return $this->getData(CustomerInterface::SALES_PERSON_ID);
    }

    public function setSalesPersonId($salesPersonId)
    {
        return $this->setData(CustomerInterface::SALES_PERSON_ID, $salesPersonId);
    }

}