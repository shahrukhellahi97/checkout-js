<?php
namespace BA\BasysCatalog\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CatalogSelection implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 0,
                'label' => 'Highest Value',
            ],
            [
                'value' => 1,
                'label' => 'Lowest Value',
            ],
            [
                'value' => 2,
                'label' => 'Regex (catalogue name)'
            ],
        ];
    }
}