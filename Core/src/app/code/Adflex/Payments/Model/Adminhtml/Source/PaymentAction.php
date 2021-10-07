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
class PaymentAction implements OptionSourceInterface
{
    /**
     * @return array|array[]
     * Implements option array for test/production mode.
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'authorize',
                'label' => __('Authorise Only')
            ],
            [
                'value' => 'authorize_capture',
                'label' => __('Authorise and Capture')
            ]
        ];
    }
}
