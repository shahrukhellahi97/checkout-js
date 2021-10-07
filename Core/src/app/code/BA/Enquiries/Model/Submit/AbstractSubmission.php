<?php
namespace BA\Enquiries\Model\Submit;

use BA\Enquiries\Api\Data\EnquiryInterface;
use BA\Enquiries\Model\Enquiry;

abstract class AbstractSubmission implements EnquirySubmissionInterface
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilderFactory
     */
    protected $transportBuilderFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \BA\Enquiries\Helper\Data
     */
    protected $enquiriesHelper;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \BA\Enquiries\Helper\FormFactory
     */
    protected $formFactory;

    public function __construct(Context $context)
    {
        $this->transportBuilderFactory = $context->getTransportBuilderFactory();
        $this->storeManager = $context->getStoreManager();
        $this->inlineTranslation = $context->getInlineTranslation();
        $this->enquiriesHelper = $context->getEnquiriesHelper();
        $this->dataPersistor = $context->getDataPersistor();
        $this->formFactory = $context->getFormFactory();
    }

    abstract public function getPersistenceKey(): string;

    abstract public function getAdminTemplateId(): ?string;

    abstract public function getCustomerTemplateId(): ?string;

    public function submit(EnquiryInterface $enquiry)
    {
        $this->inlineTranslation->suspend();
        $this->dataPersistor->set($this->getPersistenceKey(), $enquiry);

        $messages = [];

        if ($customerTemplateId = $this->getCustomerTemplateId()) {
            $customer = $this->getTransport($enquiry)
                ->setTemplateIdentifier($customerTemplateId)
                ->addTo(
                    $enquiry->getEmail(),
                    $enquiry->getContactName()
                );

            $messages[] = $customer->getTransport();
        }

        if ($adminTemplateId = $this->getAdminTemplateId()) {
            $admin = $this->getTransport($enquiry)
                ->setTemplateIdentifier($adminTemplateId)
                ->addTo(
                    $this->enquiriesHelper->getAdminEmail()
                );

            $ccs = $this->enquiriesHelper->getAdminCCs();

            if (!empty($ccs)) {
                foreach ($ccs as $email) {
                    $admin->addCc($email);
                }
            }

            $messages[] = $admin->getTransport();
        }

        try {
            foreach ($messages as $message) {
                $message->sendMessage();
            }

            $this->inlineTranslation->resume();

            return true;
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();

            return false;
        }
    }

    protected function getTransport(Enquiry $enquiry)
    {
        /** @var \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder */
        $transportBuilder = $this->transportBuilderFactory->create();

        return $transportBuilder->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId()
            ])
            ->setTemplateVars(
                $this->getTemplateVars($enquiry)
            )
            ->setFromByScope(
                [
                    'name' => $this->enquiriesHelper->getFromName(),
                    'email' => $this->enquiriesHelper->getFromEmail()
                ]
            );
    }

    protected function getTemplateVars(EnquiryInterface $enquiry)
    {
        $result = [
            'contact' => [
                'name' => $enquiry->getContactName(),
                'email' => $enquiry->getEmail(),
            ],
        ];

        return $result;
    }
}
