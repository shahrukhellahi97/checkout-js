<?php
namespace BA\Enquiries\Model\Request;

use BA\Enquiries\Api\Data\EnquiryInterface;
use Magento\Framework\App\RequestInterface;

interface RequestParserInterface
{
    /**
     * @param \Magento\Framework\App\RequestInterface $request 
     * @param \BA\Enquiries\Api\Data\EnquiryInterface $enquiry 
     * @return \BA\Enquiries\Api\Data\EnquiryInterface 
     */
    public function parse(RequestInterface $request, EnquiryInterface $enquiry);
}