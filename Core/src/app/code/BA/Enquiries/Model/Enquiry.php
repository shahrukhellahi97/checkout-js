<?php
namespace BA\Enquiries\Model;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;
use BA\Enquiries\Api\Data\EnquiryInterface;
use BA\Enquiries\Model\Field\Validate\ErrorFactory;
use BA\Enquiries\Model\Field\Validate\ValidatorInterface;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote;

class Enquiry extends DataObject implements EnquiryInterface
{
    public function getAdditionalField($name)
    {
        if ($field = $this->getData(EnquiryInterface::ADDITIONAL_FIELDS, $name)) {
            return $field;
        }

        return null;
    }

    public function getEmail()
    {
        return $this->getData(EnquiryInterface::EMAIL);
    }

    public function setEmail($email)
    {
        return $this->setData(EnquiryInterface::EMAIL, $email);
    }

    public function getContactName()
    {
        return $this->getData(EnquiryInterface::CONTACT_NAME);
    }

    public function setContactName($contactName)
    {
        return $this->setData(EnquiryInterface::CONTACT_NAME, $contactName);
    }

    public function getAdditionalFields()
    {
        if ($fields = $this->getData(EnquiryInterface::ADDITIONAL_FIELDS)) {
            return $fields;
        }

        return [];
    }

    public function setAdditionalFields($fields)
    {
        return $this->setData(EnquiryInterface::ADDITIONAL_FIELDS, $fields);
    }

    public function getItems()
    {
        return $this->getData(EnquiryInterface::ITEMS);
    }

    public function setItems($items)
    {
        return $this->setData(EnquiryInterface::ITEMS, $items);
    }
}