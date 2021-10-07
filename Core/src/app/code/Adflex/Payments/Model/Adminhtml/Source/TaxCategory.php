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
class TaxCategory extends AbstractSource
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
                'value' => 'Mixed',
                'label' => __('Mix tax rate')
            ],
            [
                'value' => 'Transferred',
                'label' => __('VAT transferred directly to Tax Authority (not invoice issuer)')
            ],
            [
                'value' => 'DutyPaidBySupplier',
                'label' => __('The duty is paid by the supplier')
            ],
            [
                'value' => 'Exempt',
                'label' => __('Item exempt from Tax')
            ],
            [
                'value' => 'FreeExportItem',
                'label' => __('Item is not taxable')
            ],
            [
                'value' => 'HigherRate',
                'label' => __('Higher rate tax item')
            ],
            [
                'value' => 'ServicesOutsideScopeOfTax',
                'label' => __('Services outside scope of tax')
            ],
            [
                'value' => 'Standard',
                'label' => __('Standard tax rate')
            ],
            [
                'value' => 'Zero',
                'label' => __('Zero rated tax')
            ],
            [
                'value' => 'OtherCode',
                'label' => __('Other tax code')
            ],
        ];
    }
}
