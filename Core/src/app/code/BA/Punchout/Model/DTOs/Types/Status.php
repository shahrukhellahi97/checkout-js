<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\StatusInterface;

class Status extends AbstractType implements StatusInterface
{
    public function getCode()
    {
        return $this->getData(StatusInterface::CODE);
    }

    public function setCode(int $code)
    {
        return $this->setData(StatusInterface::CODE, $code);
    }

    public function getText()
    {
        return $this->getData(StatusInterface::TEXT);
    }

    public function setText(string $text)
    {
        return $this->setData(StatusInterface::TEXT, $text);
    }

    public function setStatus(int $status, string $message = null)
    {
        $this->setCode($status);
        $this->setText($message);

        return $this;
    }
}