<?php
namespace BA\Enquiries\Model\Submit;

use BA\Enquiries\Helper\Summary;

class CartSummary extends AbstractSubmission
{
    const PERSISTENCE_KEY = 'enquiry_cart_summary';

    /**
     * @var \BA\Enquiries\Helper\Summary
     */
    protected $summaryHelper;

    protected function getSummaryHelper()
    {
        if (!$this->summaryHelper) {
            $this->summaryHelper = $this->formFactory->create(
                \BA\Enquiries\Helper\FormFactory::TYPE_SUMMARY
            );
        }

        return $this->summaryHelper;
    }

    public function getPersistenceKey(): string
    {
        return self::PERSISTENCE_KEY;
    }

    public function getAdminTemplateId(): ?string
    {
        return $this->getSummaryHelper()->getAdminTemplate();
    }

    public function getCustomerTemplateId(): ?string
    {
        return $this->getSummaryHelper()->getTemplate();
    }
}