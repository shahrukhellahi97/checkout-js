<?php
namespace BA\Punchout\Model\DTOs\Request;

use BA\Punchout\Api\Data\DTOs\Request\Body\SetupRequestBodyInterface;
use BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface;
use BA\Punchout\Model\DTOs\Request;

class SetupRequest extends Request implements SetupRequestInterface
{
    public function getPayload()
    {
        return $this->getData(SetupRequestInterface::REQUEST);
    }

    public function setPayload($body)
    {
        return $this->setData(SetupRequestInterface::REQUEST, $body);
    }

}