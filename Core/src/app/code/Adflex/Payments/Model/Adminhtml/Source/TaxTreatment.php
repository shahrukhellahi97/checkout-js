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
class TaxTreatment implements OptionSourceInterface
{
    /**
     * @return array|array[]
     * Implements option array for test/production mode.
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'NIL',
                'label' => __('NIL')
            ],
            [
                'value' => 'GIL',
                'label' => __('GIL')
            ],
            [
                'value' => 'NLL',
                'label' => __('NLL')
            ],
            [
                'value' => 'GLL',
                'label' => __('GLL')
            ],
            [
                'value' => 'NON',
                'label' => __('NON')
            ],
        ];
    }
}
