<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\AttributeInterface;

class Attribute extends AbstractType implements AttributeInterface
{
    public function getKey()
    {
        return $this->getData(AttributeInterface::KEY);
    }

    public function setKey(string $keyName)
    {
        return $this->setData(AttributeInterface::KEY, $keyName);
    }

    public function getValue()
    {
        return $this->getData(AttributeInterface::VALUE);
    }

    public function setValue(string $value)
    {
        return $this->setData(AttributeInterface::VALUE, $value);
    }
}