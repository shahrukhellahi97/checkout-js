<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Currency
 *
 * @package Adflex\Payments\Model\Adminhtml\Source
 */
class Currency implements OptionSourceInterface
{
    /**
     * @return array|array[]
     * Implements option array for different base currencies.
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'base',
                'label' => __('Base')
            ],
            [
                'value' => 'store',
                'label' => __('Store')
            ],
            [
                'value' => 'selector',
                'label' => __('Currency Selector')
            ]
        ];
    }
}
