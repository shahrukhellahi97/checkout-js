<?php
namespace BA\Enquiries\Model\Submit;

use BA\Enquiries\Api\Data\EnquiryInterface;

interface EnquirySubmissionInterface
{
    public function submit(EnquiryInterface $enquiry);
}