<?php
namespace BA\BasysCustomer\Model\Field;

use Magento\Framework\DataObject;

abstract class AbstractField extends DataObject implements FieldInterface
{
    public function getLabel()
    {
        return $this->getData('label');
    }

    public function getCode()
    {
        return $this->getData('code');
    }

    public function getValue()
    {
        return $this->getData('value');
    }

    public function getIsRequired()
    {
        return true;
    }
}