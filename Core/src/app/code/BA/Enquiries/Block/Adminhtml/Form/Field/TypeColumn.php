<?php
namespace BA\Enquiries\Block\Adminhtml\Form\Field;

use BA\Enquiries\Api\Data\EnquiryFieldTypeInterface;
use Magento\Framework\View\Element\Html\Select;

class TypeColumn extends Select
{
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function setInputId($value)
    {
        return $this->setId($value);
    }
    
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        
        return parent::_toHtml();
    }

    private function getSourceOptions(): array
    {
        return [
            [
                'label' => 'Text', 
                'value' => EnquiryFieldTypeInterface::TYPE_INPUT,
            ],
            [
                'label' => 'Text (multiline)', 
                'value' => EnquiryFieldTypeInterface::TYPE_TEXT,
            ],
            [
                'label' => 'Email Address', 
                'value' => EnquiryFieldTypeInterface::TYPE_INPUT_EMAIL,
            ],
            [
                'label' => 'Date', 
                'value' => EnquiryFieldTypeInterface::TYPE_INPUT_DATE,
            ],
        ];
    }
}