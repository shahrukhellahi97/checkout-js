<?php
namespace BA\Punchout\Model\DTOs;

use BA\Punchout\Api\Data\DTOs\RequestInterface;
use BA\Punchout\Api\Data\DTOs\Types\HeaderInterface;
use BA\Punchout\Model\DTOs\Types\AbstractType;
use \BA\Punchout\Model\DTOs\Types\Header;

class Request extends AbstractType implements RequestInterface
{
    /**
     * @var \BA\Punchout\Model\DTOs\Types\HeaderFactory
     */
    protected $headerFactory;
    
    public function __construct(
        \BA\Punchout\Model\DTOs\Types\HeaderFactory $headerFactory,
        array $data = []
    ) {
        $this->headerFactory = $headerFactory;
        parent::__construct($data);
    }

    public function getPayloadId()
    {
        return $this->getData(RequestInterface::PAYLOAD_ID);
    }

    public function setPayloadId(string $payloadId)
    {
        return $this->setData(RequestInterface::PAYLOAD_ID, $payloadId);
    }

    public function getTimestamp()
    {
        return $this->getData(RequestInterface::TIMESTAMP);
    }

    public function setTimestamp(string $timestamp)
    {
        return $this->setData(RequestInterface::TIMESTAMP, $timestamp);
    }

    public function getHeader()
    {
        if (!$this->hasData(RequestInterface::HEADER)) {
            $this->setHeader($this->headerFactory->create());
        }

        return $this->getData(RequestInterface::HEADER);
    }

    public function setHeader(HeaderInterface $header)
    {
        return $this->setData(RequestInterface::HEADER, $header);
    }
}