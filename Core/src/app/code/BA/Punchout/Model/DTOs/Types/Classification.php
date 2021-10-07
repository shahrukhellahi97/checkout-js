<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\ClassificationInterface;

class Classification extends AbstractType implements ClassificationInterface
{
    public function getDomain()
    {
        return $this->getData(ClassificationInterface::DOMAIN);
    }

    public function setDomain($value)
    {
        return $this->setData(ClassificationInterface::DOMAIN, $value);
    }

    public function getValue()
    {
        return $this->getData(ClassificationInterface::VALUE);
    }

    public function setValue($value)
    {
        return $this->setData(ClassificationInterface::VALUE, $value);
    }

}