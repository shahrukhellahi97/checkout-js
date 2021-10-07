<?php
namespace BA\BasysOrders\Model\PaymentType\Mutator;

use BA\BasysOrders\Helper\PaymentType as PaymentTypeHelper;
use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Model\PaymentType\MutatorInterface;

class Label implements MutatorInterface
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
        $label = $this->paymentTypeHelper->getLabelForPaymentType(
            $paymentType->getPaymentTypeId()
        );

        if ($label) {
            $paymentType->setReference($label);
        }

        /** @var \BA\BasysOrders\Api\Data\UserDefinedFieldInterface $udf */
        foreach ($paymentType->getUserDefinedFields() as $udf) {
            $label = $this->paymentTypeHelper->getLabelForUDF(
                $paymentType->getPaymentTypeId(),
                $udf->getSequenceNo(),
            );

            if ($label) {
                $udf->setCaption($label);
            }
        }

        return $paymentType;
    }
}