<?php
namespace BA\Enquiries\Model\Field\Type;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;
use BA\Enquiries\Api\Data\EnquiryFieldTypeInterface;
use BA\Enquiries\Model\Field\RendererInterface;

abstract class AbstractType implements RendererInterface
{
    public function getValidators(EnquiryFieldInterface $field)
    {
        $validations = [];

        if ($field->getIsRequired()) {
            $validations['required'] = true;
        }

        if ($field->getType() != EnquiryFieldTypeInterface::TYPE_INPUT_DATE) {
            $validations['validate-length'] = true;
        }

        return $validations;
    }

    public function getClasses()
    {
        return [];
    }

    public function getHtmlAttributes(EnquiryFieldInterface $field)
    {
        $attrs = [
            'data-validate' => json_encode(
                $this->getValidators($field),
                JSON_NUMERIC_CHECK
            ),
            'class' => implode(' ', $this->getClasses()),
            'name' => $field->getName()
        ];

        $result = [];

        foreach ($attrs as $key => $value) {
            $value = trim($value);

            if (strlen($value) >= 1) {
                $result[] = sprintf('%s=\'%s\'', $key, $value);
            }
        }

        return implode(' ', $result);
    }
}