<?php
namespace BA\Punchout\Api\Data\DTOs\Request;

use BA\Punchout\Api\Data\DTOs\Request\Body\OrderMessageBodyInterface;
use BA\Punchout\Api\Data\DTOs\RequestInterface;

interface OrderMessageInterface extends RequestInterface
{
    const REQUEST = 'payload';

    /**
     * Get Payload 
     * 
     * @return \BA\Punchout\Api\Data\DTOs\Request\Body\OrderMessageBodyInterface 
     */
    public function getPayload();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Request\Body\OrderMessageBodyInterface $body 
     * @return self
     */
    public function setPayload(OrderMessageBodyInterface $body);
}