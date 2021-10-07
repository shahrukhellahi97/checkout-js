<?php
namespace BA\BasysOrders\Model\Config\Structure\Field\Provider;

use BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface;
use BA\BasysOrders\Api\Data\PaymentTypeInterface;

class VisibleUserDefinedFields implements FieldProviderInterface
{
    /**
     * Process payment type group
     *
     * @param \BA\BasysOrders\Api\Data\PaymentTypeInterface $object
     * @return array
     */
    public function process($object)
    {
        return [
            'id' => 'visible',
            'type' => 'multiselect',
            'sortOrder' => 50,
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore' => '1',
            'options' => [
                'option' => $this->getUdfOptions($object),
            ],
            'label' => 'Active UDFs',
            '_elementType' => 'field',
        ];
    }

    public function getUdfOptions(PaymentTypeInterface $paymentType)
    {
        $result = [];

        /** @var \BA\BasysOrders\Api\Data\UserDefinedFieldInterface $udf */
        foreach ($paymentType->getUserDefinedFields() as $udf) {
            $result[] = [
                'value' => $udf->getSequenceNo(),
                'label' => $udf->getCaption(),
            ];
        }

        return $result;
    }
}