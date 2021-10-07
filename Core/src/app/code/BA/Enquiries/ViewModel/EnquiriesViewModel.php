<?php
namespace BA\Enquiries\ViewModel;

class EnquiriesViewModel extends AbstractViewModel
{
    /**
     * @var \BA\Enquiries\Helper\Summary
     */
    protected $summaryHelper;


    public function hasOtherPaymentMethods()
    {
        return !$this->enquiriesHelper->getIsEnquiryOnly();
    }

    public function getButtonText()
    {
        return __("Submit Enquiry");
    }

    public function getSuccessMessage()
    {
        return __("Thank you for your enquiry, a member of our team will be in touch shortly.");
    }

    public function getFields()
    {
        return $this->getHelper()->getFields();
    }

    public function getAjaxUrl()
    {
        return $this->urlBuilder->getUrl(
            'enquiries/enquiry/cart_summary',
            []
        );
    }

    private function getHelper()
    {
        if (!$this->summaryHelper) {
            $this->summaryHelper = $this->formFactory->create(
                \BA\Enquiries\Helper\FormFactory::TYPE_SUMMARY
            );
        }

        return $this->summaryHelper;
    }

    public function getIsEnabled()
    {
        return $this->enquiriesHelper->getIsSummaryEnabled();
    }
}
