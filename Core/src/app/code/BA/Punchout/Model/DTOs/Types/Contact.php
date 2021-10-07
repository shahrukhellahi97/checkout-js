<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\ContactInterface;

class Contact extends AttributeCollection implements ContactInterface
{
    public function getName()
    {
        return $this->getData(ContactInterface::NAME);
    }

    public function setName(string $name)
    {
        return $this->setData(ContactInterface::NAME, $name);
    }

    public function getEmail()
    {
        return $this->getData(ContactInterface::EMAIL);
    }

    public function setEmail($email)
    {
        return $this->setData(ContactInterface::EMAIL, $email);
    }
    
    public function getCurrency()
    {
        return $this->getData(ContactInterface::CURRENCY);
    }
    
    public function setCurrency(string $value)
    {
        return $this->setData(ContactInterface::CURRENCY, $value);
    }
}