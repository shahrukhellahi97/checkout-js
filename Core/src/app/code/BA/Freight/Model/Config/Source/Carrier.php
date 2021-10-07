<?php
namespace BA\Freight\Model\Config\Source;

use BA\Freight\Model\Directory\Carriers;
use Magento\Framework\Data\OptionSourceInterface;

class Carrier implements OptionSourceInterface
{
    /**
     * @var \BA\Freight\Model\Directory\Carriers
     */
    protected $carriers;

    public function __construct(
        Carriers $carriers
    ) {
        $this->carriers = $carriers;  
    }

    public function toOptionArray()
    {
        $result = [];

        foreach ($this->carriers->getAllCariers() as $carrierId => $label) {
            $result[] = [
                'label' => $label,
                'value' => $carrierId
            ];
        }

        return $result;
    }
}