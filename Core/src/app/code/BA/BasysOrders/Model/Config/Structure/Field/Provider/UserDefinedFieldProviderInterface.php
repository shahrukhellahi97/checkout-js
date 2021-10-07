<?php
namespace BA\BasysOrders\Model\Config\Structure\Field\Provider;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;

interface UserDefinedFieldProviderInterface
{
    public function process(PaymentTypeInterface $paymentType, UserDefinedFieldInterface $udf): array;
}
