<?php
namespace BA\Freight\Model\Config\Source;

use BA\Freight\Model\Carriage\CarriageMethodInterface;
use Magento\Framework\Data\OptionSourceInterface;

class CarriageMethod implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            [
                'label' => 'Fixed',
                'value' => CarriageMethodInterface::FIXED
            ],
            [
                'label' => 'Uplift %',
                'value' => CarriageMethodInterface::UPLIFT
            ],
            [
                'label' => 'Freight Matrix',
                'value' => CarriageMethodInterface::MATRIX
            ],
        ];
    }
}
