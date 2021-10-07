<?php
namespace BA\BasysOrders\Model\PaymentType\Mutator;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;
use BA\BasysOrders\Model\PaymentType\MutatorInterface;
use BA\BasysOrders\Helper\PaymentType as PaymentTypeHelper;
use Magento\Customer\Model\Session as CustomerSession;

class Values implements MutatorInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \BA\BasysOrders\Data\PaymentType
     */
    protected $paymentTypeHelper;

    public function __construct(
        PaymentTypeHelper $paymentTypeHelper,
        CustomerSession $customerSession
    ) {
        $this->paymentTypeHelper = $paymentTypeHelper;
        $this->customerSession = $customerSession;
    }
    
    public function mutate(PaymentTypeInterface $paymentType)
    {
        /** @var \BA\BasysOrders\Api\Data\UserDefinedFieldInterface $udf */
        foreach ($paymentType->getUserDefinedFields() as $udf) {
            $attribute = $this->paymentTypeHelper->getAutoFillAttributeCode(
                $paymentType->getPaymentTypeId(),
                $udf->getSequenceNo()
            );

            if ($attribute != null && $value = $this->tryGetAttributeValue($attribute)) {
                $udf->setValue($value);
            }
        }

        return $paymentType;
    }

    private function tryGetAttributeValue(string $attr)
    {
        if ($customer = $this->customerSession->getCustomer()) {
            return $customer->getData($attr);
        }

        return null;
    }
}