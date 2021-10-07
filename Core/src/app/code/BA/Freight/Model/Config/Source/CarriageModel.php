<?php
namespace BA\Freight\Model\Config\Source;

use BA\Freight\Model\Carriage\CarriageModelInterface;
use Magento\Framework\Data\OptionSourceInterface;

class CarriageModel implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            [
                'label' => 'Weight',
                'value' => CarriageModelInterface::WEIGHT
            ],
            [
                'label' => 'Value',
                'value' => CarriageModelInterface::VALUE
            ],
        ];
    }
}
