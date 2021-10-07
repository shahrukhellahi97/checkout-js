<?php
namespace BA\BasysOrders\Model\Config\Structure\Field\Provider\UserDefinedField;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;
use BA\BasysOrders\Model\Config\Structure\Field\Provider\UserDefinedFieldProviderInterface;

class Autofill implements UserDefinedFieldProviderInterface
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->customerFactory = $customerFactory;
    }

    public function process(PaymentTypeInterface $paymentType, UserDefinedFieldInterface $udf): array
    {
        return [
            'id' => 'autofill',
            'type' => 'select',
            'options' => [
                'option' => $this->getOptions()
            ],
            'label' => 'Populate',
            'comment' => 'Select attribute to populate this field with',
            '_elementType' => 'field',
        ];
    }

    private function getOptions()
    {
        $result = [];

        $result[] = [
            'label' => 'Please Select',
            'value' => '',
        ];

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->customerFactory->create();

        /** @var \Magento\Customer\Model\Attribute $attribute */
        foreach ($customer->getAttributes() as $attribute) {
            if ($attribute->getIsUserDefined()) {
                $result[] = [
                    'label' => $attribute->getStoreLabel() . '( ' . $attribute->getAttributeCode() . ')',
                    'value' => $attribute->getAttributeCode(),
                ];
            }
        }

        return $result;
    }
}