<?php
namespace BA\BasysOrders\Gateway\Request\Type;

use Magento\Payment\Gateway\Request\BuilderInterface;

class InvoiceToOpen implements BuilderInterface
{
    public function build(array $buildSubject)
    {
        return [
            'Order' => [
                'OrderHeader' => [
                    'PaymentTypeID' => 1,
                    'TransactionRef' => '',
                ]
            ],
        ];
    }
}