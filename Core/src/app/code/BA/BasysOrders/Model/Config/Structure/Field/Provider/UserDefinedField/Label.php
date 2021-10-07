<?php
namespace BA\BasysOrders\Model\Config\Structure\Field\Provider\UserDefinedField;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;
use BA\BasysOrders\Model\Config\Structure\Field\Provider\UserDefinedFieldProviderInterface;

class Label implements UserDefinedFieldProviderInterface
{
    public function process(PaymentTypeInterface $paymentType, UserDefinedFieldInterface $udf): array
    {
        return [
            'id' => 'label',
            'type' => 'text',
            'sortOrder' => 50,
            'label' => 'Label',
            '_elementType' => 'field',
        ];
    }
}