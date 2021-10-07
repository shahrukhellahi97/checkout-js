<?php
namespace BA\BasysCustomer\Model;

use BA\BasysCustomer\Api\Data\CustomerContactInterface;
use BA\BasysCustomer\Model\ResourceModel\CustomerContact as ResourceModelCustomerContact;
use Magento\Framework\Model\AbstractModel;

class CustomerContact extends AbstractModel implements CustomerContactInterface
{
    public function _construct()
    {
        $this->_init(ResourceModelCustomerContact::class);
    }

    public function getContactId()
    {
        return $this->getData(CustomerContactInterface::CONTACT_ID);
    }

    public function setContactId($contactId)
    {
        return $this->setData(CustomerContactInterface::CONTACT_ID, $contactId);
    }

    public function getEmail()
    {
        return $this->getData(CustomerContactInterface::EMAIL);
    }

    public function setEmail($emailAddress)
    {
        return $this->setData(CustomerContactInterface::EMAIL, $emailAddress);
    }

    public function getCurrency()
    {
        return $this->getData(CustomerContactInterface::CURRENCY);
    }

    public function setCurrency($currency)
    {
        return $this->setData(CustomerContactInterface::CURRENCY, $currency);
    }

    public function getDivisionId()
    {
        return $this->getData(CustomerContactInterface::DIVISION_ID);
    }

    public function setDivisionId($divisionId)
    {
        return $this->setData(CustomerContactInterface::DIVISION_ID, $divisionId);
    }

    public function getUserTypeId()
    {
        return $this->getData(CustomerContactInterface::USER_TYPE_ID);
    }

    public function setUserTypeId($userTypeId)
    {
        return $this->getData(CustomerContactInterface::USER_TYPE_ID, $userTypeId);
    }

}