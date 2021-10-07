<?php
namespace BA\Enquiries\Model\Field\Type;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;
use BA\Enquiries\Model\Field\RendererInterface;

class Textarea extends AbstractType
{
    public function toHtml(EnquiryFieldInterface $field)
    {
        return '<textarea '. $this->getHtmlAttributes($field) .' max-length="500" ></textarea>';
    }
}