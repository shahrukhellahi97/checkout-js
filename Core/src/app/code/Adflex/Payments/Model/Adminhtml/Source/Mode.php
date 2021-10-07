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
class Mode implements OptionSourceInterface
{
    /**
     * @return array|array[]
     * Implements option array for test/production mode.
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'https://api-test.adflex.co.uk/',
                'label' => __('Test')
            ],
            [
                'value' => 'https://api.adflex.co.uk/',
                'label' => __('Production')
            ]
        ];
    }

    /**
     * @param $environment
     * @return string
     * Returns selected environment.
     */
    public function getEnvironment($environment)
    {
        if ($environment == 'test') {
            return 'https://api-test.adflex.co.uk/';
        } else {
            return 'https://api.adflex.co.uk/';
        }
    }
}
