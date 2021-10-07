<?php
namespace BA\Freight\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const XML_PATH_TABLE_ID = 'carriers/%s/freight_table';

    const XML_PATH_CARRIER_ID = 'carriers/%s/carrier';
    
    const XML_PATH_CARRIAGE_ID = 'carriers/%s/carriage';

    const XML_PATH_CARRIAGE_MODEL = 'carriers/%s/freight_model';

    const XML_PATH_BREAKS = 'carriers/%s/breaks';

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\State $state,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);

        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
        $this->state = $state;
    }

    public function getCarrierId($code)
    {
        return (int) $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_CARRIER_ID, $code),
            ScopeInterface::SCOPE_STORE
        );  
    }

    public function getCarriageMethod($code)
    {
        return (int) $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_CARRIAGE_ID, $code),
            ScopeInterface::SCOPE_STORE
        );  
    }

    public function getFreightTableId($code)
    {
        return (int) $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_TABLE_ID, $code),
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCarriageModel($code)
    {
        return (int) $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_CARRIAGE_MODEL, $code),
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getBreaks($code)
    {
        return $this->serializer->unserialize($this->scopeConfig->getValue(
            sprintf(self::XML_PATH_BREAKS, $code),
            ScopeInterface::SCOPE_STORE
        ));
    }
}
