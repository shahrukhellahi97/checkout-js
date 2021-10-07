<?php
namespace BA\Enquiries\Api;

use BA\Enquiries\Api\Data\EnquiryInterface;
use Magento\Quote\Model\Quote;

interface EnquiryManagementInterface
{
    /**
     * @param \Magento\Quote\Model\Quote $object 
     * @return \BA\Enquiries\Api\Data\EnquiryInterface 
     */
    public function create(Quote $object): EnquiryInterface;
}