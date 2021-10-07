<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Level3\Types;

use Magento\Framework\Model\AbstractModel;

/**
 * Class AmexGeneralDataFormat
 *
 * @package Adflex\Payments\Model\Level3\Types
 */
class AmexGeneralDataFormat extends AbstractModel
{
    /**
     * @param $order
     * @return mixed
     * Returns amex general data format type for specified cards.
     */
    public function generateSpecificData($order)
    {
        $i = 1;
        $result = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $result['enhancedData']['generalDataFormats']['chargeDescription' . $i] = substr($item->getName(), 0, 49);
            $i++;
        }

        return $result;
    }
}
