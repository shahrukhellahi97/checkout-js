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
class TaxType extends AbstractSource
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
                'value' => 'VAT',
                'label' => __('Value Added Tax (VAT)')
            ],
            [
                'value' => 'GST',
                'label' => __('Goods and Service Tax')
            ],
            [
                'value' => 'STT',
                'label' => __('Direct Tax')
            ]
        ];
    }
}
