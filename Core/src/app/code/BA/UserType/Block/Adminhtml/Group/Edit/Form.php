<?php
namespace BA\UserType\Block\Adminhtml\Group\Edit;

use Magento\Customer\Controller\RegistryConstants;

class Form extends \Magento\Customer\Block\Adminhtml\Group\Edit\Form
{
    /**
     * @var \BA\BasysCatalog\Model\Config\Source\Catalog
     */
    protected $catalogSource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Tax\Model\TaxClass\Source\Customer $taxCustomer,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Customer\Api\Data\GroupInterfaceFactory $groupDataFactory,
        \BA\BasysCatalog\Model\Config\Source\Catalog $catalogSource,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $taxCustomer,
            $taxHelper,
            $groupRepository,
            $groupDataFactory,
            $data
        );

        $this->catalogSource = $catalogSource;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $form = $this->getForm();
        
        $fieldset = $form->addFieldset('basys_field', ['legend' => 'Basys']);

        $fieldset->addField(
            'ba_catalogues',
            'multiselect',
            [
                'name' => 'code',
                'label' => __('Catalogues'),
                'title' => __('Catalogues'),
                'required' => false,
                'values' => $this->catalogSource->toOptionArray()
            ]
        );

        $this->setForm($form);
    }
}