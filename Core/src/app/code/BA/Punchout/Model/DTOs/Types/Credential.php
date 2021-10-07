<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\CredentialInterface;

class Credential extends AttributeCollection implements CredentialInterface
{
    public function getIdentity()
    {
        return $this->getData(CredentialInterface::IDENTITY);
    }

    public function setIdentity($identity)
    {
        return $this->setData(CredentialInterface::IDENTITY, $identity);
    }
}