<?php
namespace BA\BasysOrders\Model\PaymentType;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;

interface MutatorInterface
{
    /**
     * Mutate payment
     * 
     * @param \BA\BasysOrders\Api\Data\PaymentTypeInterface $paymentType 
     * @return \BA\BasysOrders\Api\Data\PaymentTypeInterface 
     */
    public function mutate(PaymentTypeInterface $paymentType);
}
