<?php
namespace BA\BasysOrders\Model\Config\Structure\Field\Provider;

use BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface;
use BA\BasysOrders\Api\Data\PaymentTypeInterface;
use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;

class UserDefinedFields implements FieldProviderInterface
{
    /**
     * @var \BA\BasysOrders\Model\Config\Structure\Field\Provider\UserDefinedFieldProviderInterface[]|array
     */
    protected $fieldProviders;

    public function __construct(
        array $fieldProviders = []
    ) {
        $this->fieldProviders = $fieldProviders;
    }

    /**
     * Process payment type group
     *
     * @param \BA\BasysOrders\Api\Data\PaymentTypeInterface $object
     * @return array
     */
    public function process($object)
    {
        $result = [];

        /** @var \BA\BasysOrders\Api\Data\UserDefinedFieldInterface $udf */
        foreach ($object->getUserDefinedFields() as $udf) {
            $key = 'udf' . $udf->getSequenceNo();

            $result[] = [
                'id'            => $key,
                'label'         => $udf->getCaption(),
                'showInDefault' => '1',
                'showInWebsite' => '1',
                'showInStore'   => '1',
                '_elementType'  => 'group',
                'path'          => 'basys_store/p' . $object->getPaymentTypeId(),
                'children'      => $this->getGroupChildren($object, $udf)
            ];
        }

        return $result;
    }

    protected function getGroupChildren(PaymentTypeInterface $object, UserDefinedFieldInterface $udf)
    {
        $result = [];

        $defaults = [
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore' => '1',
            'module_name' => 'BA_BasysOrders',
            'path' => sprintf(
                'basys_store/p%s/udf%s',
                $object->getPaymentTypeId(),
                $udf->getSequenceNo(),
            )
        ];

        /** @var \BA\BasysOrders\Model\Config\Structure\Field\Provider\UserDefinedFieldProviderInterface $provider */
        foreach ($this->fieldProviders as $provider) {
            $result[] = array_merge(
                $defaults,
                $provider->process($object, $udf)
            );
        }

        return $result;
    }
}
