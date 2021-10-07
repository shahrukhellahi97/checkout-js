<?php
namespace BA\BasysOrders\Ui\Methods;

use Magento\Checkout\Model\ConfigProviderInterface;

class ConsolidatedInvoiceConfigProvider implements ConfigProviderInterface
{
    const CODE = 'ba_invoice_to_open';

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'udf' => [
                        [
                            'label' => 'PO Number',
                            'sequence' => 1,
                            'value' => '',
                        ],
                        [
                            'label' => 'Reference Number',
                            'sequence' => 2,
                            'value' => 'Testing',
                        ],
                    ]
                ]
            ]
        ];
    }
}
