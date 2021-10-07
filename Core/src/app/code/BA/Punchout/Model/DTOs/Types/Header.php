<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\CredentialInterface;
use BA\Punchout\Api\Data\DTOs\Types\HeaderInterface;
use BA\Punchout\Model\CredentialFactory;

class Header extends AbstractType implements HeaderInterface
{
    /**
     * @var \BA\Punchout\Api\Data\DTOs\Types\CredentialFactory
     */
    protected $credentialFactory;

    public function __construct(
        \BA\Punchout\Model\DTOs\Types\CredentialFactory $credentialFactory,
        array $data = []
    ) {
        $this->credentialFactory = $credentialFactory;
        parent::__construct($data);
    }
    public function getTo()
    {
        if (!$this->hasData(HeaderInterface::TO)) {
            $this->setTo($this->credentialFactory->create());
        }

        return $this->getData(HeaderInterface::TO);
    }

    public function setTo($credential)
    {
        return $this->setData(HeaderInterface::TO, $credential);
    }

    public function getFrom()
    {
        if (!$this->hasData(HeaderInterface::FROM)) {
            $this->setFrom($this->credentialFactory->create());
        }
        

        return $this->getData(HeaderInterface::FROM);
    }

    public function setFrom($credential)
    {
        return $this->setData(HeaderInterface::FROM, $credential);
    }

    public function getSender()
    {
        if (!$this->hasData(HeaderInterface::SENDER)) {
            $this->setSender($this->credentialFactory->create());
        }
        
        return $this->getData(HeaderInterface::SENDER);
    }

    public function setSender($credential)
    {
        return $this->setData(HeaderInterface::SENDER, $credential);
    }

}