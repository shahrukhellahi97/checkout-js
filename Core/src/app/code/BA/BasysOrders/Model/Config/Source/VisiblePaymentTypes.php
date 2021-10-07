<?php
namespace BA\BasysOrders\Model\Config\Source;

use BA\BasysOrders\Api\PaymentTypeManagmentInterface;
use Magento\Framework\Data\OptionSourceInterface;

class VisiblePaymentTypes implements OptionSourceInterface
{
    /**
     * @var \BA\BasysOrders\Api\PaymentTypeManagmentInterface
     */
    protected $paymentTypeManagment;

    public function __construct(
        PaymentTypeManagmentInterface $paymentTypeManagment
    ) {
        $this->paymentTypeManagment = $paymentTypeManagment;
    }

    public function toOptionArray()
    {
        $result = [];

        /** @var \BA\BasysOrders\Api\Data\PaymentTypeInterface $paymentType */
        foreach ($this->paymentTypeManagment->getVisiblePaymentTypes() as $paymentType) {
            $result[] = [
                'label' => $paymentType->getReference(),
                'value' => $paymentType->getPaymentTypeId()
            ];
        }

        return $result;
    }
}