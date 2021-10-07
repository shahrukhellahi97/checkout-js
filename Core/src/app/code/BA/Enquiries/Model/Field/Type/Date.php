<?php
namespace BA\Enquiries\Model\Field\Type;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;

class Date extends AbstractType
{
    const INPUT_TYPE = 'text';

    public function toHtml(EnquiryFieldInterface $field)
    {
        return '
            <input type="text" '. $this->getHtmlAttributes($field) .' max-length="12" />
        ';
    }

    public function getClasses(): array
    {
        return ['date-picker'];
    }
}