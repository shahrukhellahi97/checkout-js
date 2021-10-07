<?php
namespace BA\Punchout\Api\Data\DTOs\Request;

use BA\Punchout\Api\Data\DTOs\Request\Body\SetupRequestBodyInterface;
use BA\Punchout\Api\Data\DTOs\RequestInterface;

interface SetupRequestInterface extends RequestInterface
{
    const REQUEST = 'request';

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Request\Body\SetupRequestBodyInterface 
     */
    public function getPayload();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Request\Body\SetupRequestBodyInterface $body 
     * @return self
     */
    public function setPayload($body);
}