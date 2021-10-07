<?php
namespace BA\Enquiries\Model\Field\Type;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;

class Email extends AbstractType
{
    public function toHtml(EnquiryFieldInterface $field)
    {
        return '
            <input type="email" '. $this->getHtmlAttributes($field) .' max-length="128" />
        ';
    }
}