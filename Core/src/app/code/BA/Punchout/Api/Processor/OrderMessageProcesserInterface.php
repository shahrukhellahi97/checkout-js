<?php
namespace BA\Punchout\Api\Processor;

use BA\Punchout\Api\Data\DTOs\Request\OrderMessageInterface;

interface OrderMessageProcesserInterface
{
    public function setRequestId(int $id);

    /**
     * @return \BA\Punchout\Model\Request
     */
    public function getSetupRequest();

    public function getOrderMessage(): OrderMessageInterface;
}