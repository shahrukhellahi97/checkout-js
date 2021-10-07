<?php
namespace BA\BasysGiftCertificate\Gatewa\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class GiftCertificateBuilder implements BuilderInterface
{
    public function build(array $buildSubject)
    {
        if (true) {
            return [
                'Order' => [
                    'OrderHeader' => [
                        'GiftCertificates' => [
                            // ''
                        ]
                    ],
                ],
            ];
        }
    }
}