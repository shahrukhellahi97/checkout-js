<?php
namespace BA\BasysOrders\Model\Config\Structure\Field;

use BA\BasysCatalog\Model\Config\Structure\Field\AbstractDynamicField;
use BA\BasysOrders\Api\PaymentTypeManagmentInterface;
use Magento\Framework\Module\ModuleListInterface;

class Data extends AbstractDynamicField
{
    /**
     * @var \BA\BasysOrders\Api\PaymentTypeManagmentInterface
     */
    protected $paymentTypeManagment;

    public function __construct(
        ModuleListInterface $moduleList,
        PaymentTypeManagmentInterface $paymentTypeManagment,
        $fieldProviders = []
    ) {
        parent::__construct($moduleList, $fieldProviders);
        $this->paymentTypeManagment = $paymentTypeManagment;
    }

    public function getGroups()
    {
        $groups = [];

        /** @var \BA\BasysOrders\Api\Data\PaymentTypeInterface $paymentType */
        foreach ($this->paymentTypeManagment->getVisiblePaymentTypes() as $paymentType) {
            $key = 'p' . $paymentType->getPaymentTypeId();

            $groups[$key] = [
                'id'            => $key,
                'label'         => $paymentType->getReference(),
                'showInDefault' => '1',
                'showInWebsite' => '1',
                'showInStore'   => '1',
                '_elementType'  => 'group',
                'path'          => $this->getSection(),
                'children'      => $this->process($paymentType, $key)
            ];
        }

        return $groups;
    }

    public function getModule()
    {
        return 'BA_BasysOrders';
    }

    public function getSection()
    {
        return 'basys_store';
    }

    public function getTab()
    {
        return 'brandaddition';
    }
}
