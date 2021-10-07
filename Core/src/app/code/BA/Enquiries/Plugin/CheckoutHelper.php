<?php
namespace BA\Enquiries\Plugin;

use BA\Enquiries\Helper\Data;

class CheckoutHelper
{
    /**
     * @var \BA\Enquiries\Helper\Data
     */
    protected $enquiriesHelper;

    public function __construct(
        Data $enquiriesHelper
    ) {
        $this->enquiriesHelper = $enquiriesHelper;
    }

    public function afterCanOnepageCheckout(
        \Magento\Checkout\Helper\Data $subject,
        $result
    ) {
        if ($this->enquiriesHelper->getIsEnquiryOnly()) {
            return false;
        }

        return $result;
    }

    public function afterIsMultishippingCheckoutAvailable(
        \Magento\Multishipping\Helper\Data $subject,
        $result
    ) {
        if ($this->enquiriesHelper->getIsEnquiryOnly()) {
            return false;
        }

        return $result;
    }
}