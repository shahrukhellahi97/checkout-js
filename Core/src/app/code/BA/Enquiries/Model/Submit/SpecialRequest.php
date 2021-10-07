<?php
namespace BA\Enquiries\Model\Submit;

class SpecialRequest extends AbstractSubmission
{
    const PERSISTENCE_KEY = 'enquiry_cart_summary';

    /**
     * @var \BA\Enquiries\Helper\Special
     */
    protected $specialHelper;

    protected function getSpecialHelper()
    {
        if (!$this->specialHelper) {
            $this->specialHelper = $this->formFactory->create(
                \BA\Enquiries\Helper\FormFactory::TYPE_SPECIAL
            );
        }

        return $this->specialHelper;
    }


    public function getPersistenceKey(): string
    {
        return self::PERSISTENCE_KEY;
    }

    public function getAdminTemplateId(): ?string
    {
        return $this->getSpecialHelper()->getAdminTemplate();
    }

    public function getCustomerTemplateId(): ?string
    {
        return $this->getSpecialHelper()->getTemplate();
    }
}
