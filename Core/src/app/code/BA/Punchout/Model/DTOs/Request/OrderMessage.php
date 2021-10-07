<?php
namespace BA\Punchout\Model\DTOs\Request;

use BA\Punchout\Api\Data\DTOs\Request\Body\OrderMessageBodyInterface;
use BA\Punchout\Api\Data\DTOs\Request\OrderMessageInterface;
use BA\Punchout\Model\DTOs\Request;

class OrderMessage extends Request implements OrderMessageInterface
{
    protected $orderMessageBodyFactory;

    public function __construct(
        \BA\Punchout\Model\DTOs\Types\HeaderFactory $headerFactory,
        \BA\Punchout\Model\DTOs\Request\Body\OrderMessageBodyFactory $orderMessageBodyFactory,
        array $data = []
    ) {  
        $this->orderMessageBodyFactory = $orderMessageBodyFactory;
        parent::__construct($headerFactory, $data);
    }

    public function getPayload()
    {
        if (!$this->hasData(OrderMessageInterface::REQUEST)) {
            $this->setPayload($this->orderMessageBodyFactory->create());
        }

        return $this->getData(OrderMessageInterface::REQUEST);
    }

    public function setPayload(OrderMessageBodyInterface $body)
    {
        return $this->setData(OrderMessageInterface::REQUEST, $body);
    }

}