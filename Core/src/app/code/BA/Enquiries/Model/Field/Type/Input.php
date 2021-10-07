<?php
namespace BA\Enquiries\Model\Field\Type;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;

class Input extends AbstractType
{
    public function toHtml(EnquiryFieldInterface $field)
    {
        return '
            <input type="text" ' . $this->getHtmlAttributes($field) . ' max-length="128" />
        ';
    }
}
