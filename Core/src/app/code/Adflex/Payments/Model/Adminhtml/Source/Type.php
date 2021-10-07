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
class Type implements OptionSourceInterface
{
    /**
     * @return array|array[]
     * Implements option array for test/production mode.
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'inline',
                'label' => __('Inline')
            ],
            [
                'value' => 'lightbox',
                'label' => __('Lightbox')
            ]
        ];
    }
}
