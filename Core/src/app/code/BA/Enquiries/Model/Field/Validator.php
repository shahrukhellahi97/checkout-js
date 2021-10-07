<?php
namespace BA\Enquiries\Model\Field;

use BA\Enquiries\Api\Data\EnquiryFieldTypeInterface;
use BA\Enquiries\Model\AbstractValidator;
use BA\Enquiries\Model\EnquiryField;

class Validator extends AbstractValidator
{
    public function isValid(
        \Magento\Framework\DataObject $object
    ) {
        if ($object instanceof EnquiryField) {
            $this->addRulesType($object);
            $x = $this->getValidator();

            return $this->getValidator()->isValid($object);
        }

        return false;
    }

    protected function addRulesType(EnquiryField $field)
    {
        if ($field->getIsRequired()) {
            $rule = new \Magento\Framework\Validator\NotEmpty();

            $this->getValidator()->addRule($rule, 'value');
        }

        if ($field->getType() == EnquiryFieldTypeInterface::TYPE_INPUT_EMAIL) {
            $emailValid = new \Magento\Framework\Validator\EmailAddress();
            $emailValid->setMessage(
                __('Valid email required'),
                \Zend_Validate_EmailAddress::INVALID,
            );

            $this->getValidator()->addRule($emailValid, 'value');
        }

        $lengthValid = new \Magento\Framework\Validator\StringLength();
        $lengthValid->setMax(
            $field->getType() == EnquiryFieldTypeInterface::TYPE_TEXT 
                ? 500
                : 128
        );

        // $lengthValid->setMin(100);

        $this->getValidator()->addRule($lengthValid, 'value');
    }
}
