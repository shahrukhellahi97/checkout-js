<?php
namespace BA\UserType\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Catalogs implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            [
                'label' => 'A',
                'value' => 1,
            ],
            [
                'label' => 'B',
                'value' => 2,
            ],
            [
                'label' => 'C',
                'value' => 3,
            ],
        ];
    }
}