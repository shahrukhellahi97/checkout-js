<?php
namespace BA\Punchout\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class PunchoutOrderMessageBuilder implements BuilderInterface
{
    public function build(array $buildSubject)
    {
        return [];
    }
}