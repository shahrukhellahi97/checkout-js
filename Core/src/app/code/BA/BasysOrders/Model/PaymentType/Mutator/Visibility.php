<?php
namespace BA\BasysOrders\Model\PaymentType\Mutator;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Helper\PaymentType as PaymentTypeHelper;
use BA\BasysOrders\Model\PaymentType\MutatorInterface;

class Visibility implements MutatorInterface
{
    /**
     * @var \BA\BasysOrders\Helper\PaymentType
     */
    protected $paymentTypeHelper;

    public function __construct(
        PaymentTypeHelper $paymentTypeHelper
    ) {
        $this->paymentTypeHelper = $paymentTypeHelper;
    }

    public function mutate(PaymentTypeInterface $paymentType)
    {
        return $paymentType;
    }
}
