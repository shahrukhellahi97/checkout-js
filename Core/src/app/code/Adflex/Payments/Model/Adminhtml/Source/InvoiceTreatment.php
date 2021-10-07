<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Mode
 *
 * @package Adflex\Payments\Model\Adminhtml\Source
 */
class InvoiceTreatment implements OptionSourceInterface
{
    /**
     * @return array|array[]
     * Implements option array for test/production mode.
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'Printed',
                'label' => __('Invoice is printed to paper')
            ],
            [
                'value' => 'Supplemental',
                'label' => __('A supplemental invoice is supplied')
            ],
            [
                'value' => 'Suppressed',
                'label' => __('The invoice is suppressed')
            ],
        ];
    }
}
