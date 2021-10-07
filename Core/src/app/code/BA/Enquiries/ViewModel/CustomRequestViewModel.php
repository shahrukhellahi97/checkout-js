<?php
namespace BA\Enquiries\ViewModel;

class CustomRequestViewModel extends AbstractViewModel
{
    /**
     * @var \BA\Enquiries\Helper\Special
     */
    protected $specialHelper;

    /**
     * @return \BA\Enquiries\Model\EnquiryField[]
     */
    public function getFields()
    {
        return $this->getHelper()->getFields();
    }

    private function getHelper()
    {
        if (!$this->specialHelper) {
            $this->specialHelper = $this->formFactory->create(
                \BA\Enquiries\Helper\FormFactory::TYPE_SPECIAL
            );
        }

        return $this->specialHelper;
    }

    public function getAjaxUrl()
    {
        return $this->urlBuilder->getUrl(
            'enquiries/custom/submit',
            []
        );
    }
}
