<?php
namespace BA\BasysOrders\Block\Adminhtml\Form;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class PaymentTypeFieldArray extends AbstractFieldArray
{
    /**
     * @var \Magento\Framework\View\Element\BlockInterface
     */
    protected $radioRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn(
            'label',
            [
                'label' => __('Label'),
                'class' => 'required-entry',
            ]
        );

        $this->addColumn(
            'xxxx',
            [
                'label' => __('Active'),
                'type' => 'checkbox',
                'class' => 'required-entry',
            ]
        );

        $this->addColumn(
            'active',
            [
                'label' => __('Active'),
                'type' => 'checkbox',
                'class' => 'required-entry',
            ]
        );

        $this->_addAfter = false;
    }
}