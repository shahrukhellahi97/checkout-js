<?php
namespace BA\Punchout\Model\DTOs\Request\Body;

use BA\Punchout\Api\Data\DTOs\Types\ContactInterface;
use BA\Punchout\Api\Data\DTOs\Request\Body\SetupRequestBodyInterface;
use BA\Punchout\Api\Data\DTOs\Types\AddressInterface;
use BA\Punchout\Api\Data\DTOs\Types\UrlInterface;
use BA\Punchout\Model\DTOs\Types\AbstractType;

class SetupRequestBody extends AbstractType implements SetupRequestBodyInterface
{
    protected $urlFactory;

    public function __construct(
        \BA\Punchout\Api\Data\DTOs\Types\ContactInterfaceFactory $contactFactory,
        \BA\Punchout\Api\Data\DTOs\Types\UrlInterfaceFactory $urlFactory)
    {
        $this->setBrowserFromPost($urlFactory->create());
        $this->setReturnUrl($urlFactory->create());

        $this->setContact($contactFactory->create());
    }

    public function getBuyerCookie()
    {
        return $this->getData(SetupRequestBodyInterface::BUYER_COOKIE);
    }

    public function setBuyerCookie($cookie)
    {
        return $this->setData(SetupRequestBodyInterface::BUYER_COOKIE, $cookie);
    }

    public function getBrowserFromPost()
    {
        return $this->getData(SetupRequestBodyInterface::BROWSER_FROM_POST);
    }

    public function setBrowserFromPost($url)
    {
        return $this->setData(SetupRequestBodyInterface::BROWSER_FROM_POST, $url);
    }

    public function getReturnUrl()
    {
        return $this->getData(SetupRequestBodyInterface::RETURN_URL);
    }

    public function setReturnUrl($url)
    {
        return $this->setData(SetupRequestBodyInterface::RETURN_URL, $url);
    }

    public function getContact()
    {
        return $this->getData(SetupRequestBodyInterface::CONTACT);
    }

    public function setContact($contact)
    {
        return $this->setData(SetupRequestBodyInterface::CONTACT, $contact);
    }

    public function getShipTo()
    {
        return $this->getData(SetupRequestBodyInterface::SHIP_TO);
    }

    public function setShipTo($address)
    {
        return $this->setData(SetupRequestBodyInterface::SHIP_TO, $address);
    }

}