<?php
namespace BA\Enquiries\Block\Adminhtml\Form\Field;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Config\Model\Config\Source\Email\Template;

class EmailFields extends AbstractFieldArray
{
    protected function _prepareToRender()
    {
        $this->addColumn('email', [
            'label' => 'Email Address',
            'class' => 'required-entry'
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}