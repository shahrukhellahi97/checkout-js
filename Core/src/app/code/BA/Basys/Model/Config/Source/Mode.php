<?php
namespace BA\Basys\Model\Config\Source;

use BA\Basys\Model\ModeInterface;

class Mode implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['label' => 'Staging',    'value' => ModeInterface::STAGING],
            ['label' => 'Production', 'value' => ModeInterface::PRODUCTION]
        ];
    }
}