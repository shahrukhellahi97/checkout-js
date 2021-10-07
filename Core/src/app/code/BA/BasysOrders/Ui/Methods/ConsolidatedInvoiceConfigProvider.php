<?php
namespace BA\BasysOrders\Ui\Methods;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\Data\PaymentTypeMethodMetadataInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;
use BA\BasysOrders\Api\PaymentTypeManagmentInterface;
use BA\BasysOrders\Model\PaymentType\MutatorInterface;
use Magento\Checkout\Model\ConfigProviderInterface;

class ConsolidatedInvoiceConfigProvider implements ConfigProviderInterface
{
    const CODE = 'ba_consolidated_invoice';

    /**
     * @var \BA\BasysOrders\Api\PaymentTypeManagmentInterface
     */
    protected $paymentTypeManagment;

    /**
     * @var \BA\BasysOrders\Model\PaymentType\MutatorInterface
     */
    protected $mutator;

    public function __construct(
        PaymentTypeManagmentInterface $paymentTypeManagment,
        MutatorInterface $mutator
    ) {
        $this->paymentTypeManagment = $paymentTypeManagment;
        $this->mutator = $mutator;
    }

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'types' => $this->getPaymentTypes(),
                ]
            ]
        ];
    }

    private function getPaymentTypes()
    {
        $paymentTypes = $this->paymentTypeManagment->getVisiblePaymentTypes([
            PaymentTypeMethodMetadataInterface::METHOD_INVOICE,
            PaymentTypeMethodMetadataInterface::METHOD_CONSOLIDATED_INVOICE,
        ]);

        $result = [];

        /** @var \BA\BasysOrders\Api\Data\PaymentTypeInterface $type */
        foreach ($paymentTypes as $paymentType) {
            $type = $this->mutator->mutate($paymentType);

            $result[] = [
                'label' => trim($type->getReference()),
                'value' => $type->getPaymentTypeId(),
                'udfs'  => $this->getUserDefinedFields($type)
            ];
        }

        return $result;
    }

    private function getUserDefinedFields(PaymentTypeInterface $paymentType)
    {
        $result = [];

        /** @var \BA\BasysOrders\Api\Data\UserDefinedFieldInterface $udf */
        foreach ($paymentType->getUserDefinedFields() as $udf) {
            $value = $this->getOptionsForUDF($udf);

            $result[] = [
                'label' => trim($udf->getCaption()),
                'type' => trim($udf->getRule()),
                'sequence' => $udf->getSequenceNo(),
                'value' => $value,
            ];
        }

        return $result;
    }

    private function getOptionsForUDF(UserDefinedFieldInterface $userDefinedField)
    {
        $result = [];
        
        foreach ($userDefinedField->getOptions() as $option) {
            $result[] = [
                'value' => $option->getOptionId(),
                'label' => $option->getValue(),
            ];
        }
        
        if (count($result) >= 1) {
            return $result;
        }

        if ($newValue = $userDefinedField->getValue()) {
            return $newValue;
        }

        return '';
    }
}
