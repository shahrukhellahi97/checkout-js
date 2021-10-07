<?php
namespace BA\BasysOrders\Model\PaymentType;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;

class Mutator implements MutatorInterface
{
    /**
     * @var \BA\BasysOrders\Model\PaymentType\MutatorInterface[]|array
     */
    protected $mutators;

    public function __construct(
        array $mutators = []
    ) {
        $this->mutators = $mutators;
    }

    public function mutate(PaymentTypeInterface $paymentType)
    {
        foreach ($this->mutators as $mutator) {
            $paymentType = $mutator->mutate($paymentType);
        }

        return $paymentType;
    }
}