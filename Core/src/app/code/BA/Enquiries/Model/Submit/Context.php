<?php
namespace BA\Enquiries\Model\Submit;

class Context
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

    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilderFactory $transportBuilderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \BA\Enquiries\Helper\Data $enquiriesHelper,
        \BA\Enquiries\Helper\FormFactory $formFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->transportBuilderFactory = $transportBuilderFactory;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->enquiriesHelper = $enquiriesHelper;
        $this->dataPersistor = $dataPersistor;
        $this->formFactory = $formFactory;
    }

    public function getFormFactory()
    {
        return $this->formFactory;
    }

    public function getTransportBuilderFactory()
    {
        return $this->transportBuilderFactory;
    }

    public function getStoreManager()
    {
        return $this->storeManager;
    }

    public function getInlineTranslation()
    {
        return $this->inlineTranslation;
    }

    public function getEnquiriesHelper()
    {
        return $this->enquiriesHelper;
    }

    public function getDataPersistor()
    {
        return $this->dataPersistor;
    }

}