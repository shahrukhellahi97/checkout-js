<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\AddressInterface;

class Address extends AttributeCollection implements AddressInterface
{
    public function getDeliverTo()
    {
        return $this->getData(AddressInterface::DELIVER_TO);
    }

    public function setDeliverTo(string $value)
    {
        return $this->setData(AddressInterface::DELIVER_TO, $value);
    }

    public function getStreet()
    {
        return $this->getData(AddressInterface::STREET);
    }

    public function setStreet(string $value)
    {
        return $this->setData(AddressInterface::STREET, $value);
    }

    public function getCity()
    {
        return $this->getData(AddressInterface::CITY);
    }

    public function setCity(string $value)
    {
        return $this->setData(AddressInterface::CITY, $value);
    }

    public function getState()
    {
        return $this->getData(AddressInterface::STATE);
    }

    public function setState(string $value)
    {
        return $this->setData(AddressInterface::STATE, $value);
    }

    public function getPostalCode()
    {
        return $this->getData(AddressInterface::POSTCODE);
    }

    public function setPostalCode(string $value)
    {
        return $this->setData(AddressInterface::POSTCODE, $value);
    }

    public function getCountry()
    {
        return $this->getData(AddressInterface::COUNTRY);
    }

    public function setCountry(string $country)
    {
        return $this->setData(AddressInterface::COUNTRY, $country);
    }
}