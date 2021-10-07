<?php
namespace BA\BasysOrders\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class InitializeDataBuilder implements BuilderInterface
{
    public function build(array $buildSubject)
    {
        return [];
    }
}