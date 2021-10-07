<?php
namespace BA\BasysOrders\Model\Config\Structure\Field\Provider;

use BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface;

class Label implements FieldProviderInterface
{
    /**
     * Process payment type group
     *
     * @param \BA\BasysOrders\Api\Data\PaymentTypeInterface $object
     * @return array
     */
    public function process($object)
    {
        return [
            'id' => 'label',
            'type' => 'text',
            'sortOrder' => 50,
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore' => '1',
            'label' => 'Label',
            '_elementType' => 'field',
        ];
    }
}