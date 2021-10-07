<?php
namespace BA\Enquiries\Model\Request;

use Magento\Framework\App\RequestInterface;
use BA\Enquiries\Api\Data\EnquiryInterface;

class Parser implements RequestParserInterface
{
    public function parse(RequestInterface $request, EnquiryInterface $enquiry)
    {
        $enquiry->setContactName(
            $request->getParam(EnquiryInterface::CONTACT_NAME)
        );

        $enquiry->setEmail(
            $request->getParam(EnquiryInterface::EMAIL)
        );

        /** @var \BA\Enquiries\Api\Data\EnquiryFieldInterface $field */
        foreach ($enquiry->getAdditionalFields() as $field) {
            $field->setValue(
                $request->getParam($field->getName())
            );
        }

        return $enquiry;
    }
}