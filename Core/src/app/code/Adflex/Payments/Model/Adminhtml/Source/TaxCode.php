<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Adminhtml\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Mode
 *
 * @package Adflex\Payments\Model\Adminhtml\Source
 */
class TaxCode extends AbstractSource
{
    /**
     * @return array|array[]
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }

    /**
     * @return array|array[]
     * Implements option array for test/production mode.
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'Standard',
                'label' => __('Standard')
            ],
            [
                'value' => 'Zero',
                'label' => __('Tax Free')
            ],
            [
                'value' => 'Exempt',
                'label' => __('Tax Exempt')
            ],
            [
                'value' => 'Reduced',
                'label' => __('Tax Reduction')
            ]
        ];
    }
}
