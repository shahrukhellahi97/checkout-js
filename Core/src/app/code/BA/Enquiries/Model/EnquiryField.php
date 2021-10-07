<?php
namespace BA\Enquiries\Model;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;
use Magento\Framework\DataObject;

class EnquiryField extends DataObject implements EnquiryFieldInterface
{
    public function getName()
    {
        if ($name = $this->getData(EnquiryFieldInterface::NAME)) {
            return $name;
        }

        return strtolower(preg_replace('/([^\w+])/i', '', $this->getLabel()));
    }

    public function setName($name)
    {
        return $this->setData(EnquiryFieldInterface::NAME, $name);
    }

    public function getLabel()
    {
        return $this->getData(EnquiryFieldInterface::LABEL);
    }

    public function setLabel($label)
    {
        return $this->setData(EnquiryFieldInterface::LABEL, $label);
    }

    public function getValue()
    {
        return $this->getData(EnquiryFieldInterface::VALUE);
    }

    public function setValue($value)
    {
        return $this->setData(EnquiryFieldInterface::VALUE, $value);
    }

    public function getIsRequired()
    {
        return $this->getData(EnquiryFieldInterface::IS_REQUIRED);
    }

    public function setIsRequired($value)
    {
        return $this->setData(EnquiryFieldInterface::IS_REQUIRED, $value);
    }

    public function getType()
    {
        return $this->getData(EnquiryFieldInterface::TYPE);
    }

    public function setType($type)
    {
        return $this->setData(EnquiryFieldInterface::TYPE, $type);
    }
}