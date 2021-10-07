<?php
namespace BA\Enquiries\Model\Field;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;

interface RendererInterface
{
    /**
     * @param \BA\Enquiries\Api\Data\EnquiryFieldInterface $enquiryField 
     * @return string 
     */
    public function toHtml(EnquiryFieldInterface $enquiryField);
}