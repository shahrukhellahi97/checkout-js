<?php
namespace BA\Punchout\Model\DTOs\Types;

use BA\Punchout\Api\Data\DTOs\Types\MoneyInterface;

class Money extends AbstractType implements MoneyInterface
{
    public function getValue()
    {
        return $this->getData(MoneyInterface::VALUE);
    }

    public function setValue(float $value)
    {
        return $this->setData(MoneyInterface::VALUE, $value);
    }

    public function getCurrency()
    {
        return $this->getData(MoneyInterface::CURRENCY);
    }

    public function setCurrency(string $currency)
    {
        return $this->setData(MoneyInterface::CURRENCY, $currency);
    }
}