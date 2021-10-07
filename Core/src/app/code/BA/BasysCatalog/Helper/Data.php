<?php
namespace BA\BasysCatalog\Helper;

use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const XML_PATH_DIVISION_ID = 'basys_store/store/division_id';

    const XML_PATH_CATALOGS = 'basys_store/store/active';

    const XML_PATH_CATALOG_EXPIRATION = 'basys_store/store/expiration';

    const XML_PATH_DEFAULT_SELECTION = 'basys_store/store/catalog_selection';

    const XML_PATH_DEFAULT_SELECTION_REGEX = 'basys_store/store/catalog_regex';

    const XML_PATH_SOURCE_CODE_ID = 'basys_catalog/c%s/source_code';
    
    const XML_PATH_CUSTOMER_ID = 'basys_catalog/c%s/customer';

    const XML_PATH_KEY_GROUP = 'basys_catalog/c%s/key_group';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\State $state,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {

        parent::__construct($context);

        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->state = $state;
    }

    public function getCatalogueExpiration($storeCode = null)
    {
        $hours = max(0, (int) $this->scopeConfig->getValue(
            self::XML_PATH_CATALOG_EXPIRATION,
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        ));

        return 60 * 60 * $hours;
    }

    /**
     * Get the custom price/tier prices
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param int $itemQty
     * @return float
     */
    public function getPrice(ProductInterface $product, $itemQty)
    {
        try {
            $prices = $this->productResolver->get($product)->getPrices();
            $priceVal = 0;

            foreach ($prices as $price) {
                /* assuming type id proportional to break/price qty  and get the prices on desc order */
                if ($itemQty >= $price->getBreak()) {
                    $priceVal = $price->getPrice();

                    break;
                }
            }
            return $priceVal;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
    
    public function getCatalogSelectionRegex($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_SELECTION_REGEX,
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );
    }

    public function getCatalogSelectionMethod($storeCode = null)
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_SELECTION,
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );

        return (int) $value;
    }

    /**
     * Return array of active catalog ids
     * @param string|null $storeCode
     * @return array
     */
    public function getActiveCatalogIds($storeCode = null)
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_CATALOGS,
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );
        
        return $value != null ? explode(',', $value) : [];
    }

    /**
     * @return string
     */
    public function getDivisionId($storeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DIVISION_ID,
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );
    }

    public function getSourceCodeId($catalog, $storeCode = null)
    {
        return $this->scopeConfig->getValue(
            $this->getXmlPath(self::XML_PATH_SOURCE_CODE_ID, $catalog),
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );
    }

    public function getDefaultCustomerId($catalog, $storeCode = null)
    {
        return $this->scopeConfig->getValue(
            $this->getXmlPath(self::XML_PATH_CUSTOMER_ID, $catalog),
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );
    }

    public function getKeyGroupId($catalog, $storeCode = null)
    {
        return $this->scopeConfig->getValue(
            $this->getXmlPath(self::XML_PATH_KEY_GROUP, $catalog),
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );
    }

    public function getCurrentStoreId()
    {
        if ($this->state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
            return $this->getAdminhtmlStoreId();
        } else {
            return null;
        }
    }

    private function getAdminhtmlStoreId()
    {
        // Due to the way we implement our catalogue configuration & how magento parses URL params
        // in adminhtml into the the request object, we are forced to do this ugliness
        // If you can think of a cleaner/better way to this, then please refactor!
        $url = $this->_request->getParam('isAjax') == null
            ? $_SERVER['REQUEST_URI']
            : $_SERVER['HTTP_REFERER'];

        if (preg_match('/(website|store)\/([0-9]+)/', $url, $matches)) {
            if ($matches[1] == 'website') {
                return $matches[2];
            } else {
                return $this->storeManager->getStore($matches[2])->getWebsiteId();
            }
        }

        return null;
    }

    private function getXmlPath($path, $catalog)
    {
        return sprintf(
            $path,
            $this->getCatalogId($catalog)
        );
    }

    private function getCatalogId($catalog)
    {
        if ($catalog instanceof \BA\BasysCatalog\Api\Data\CatalogInterface) {
            return $catalog->getId();
        }

        return (int) $catalog;
    }

    public function getModuleName()
    {
        return $this->_moduleName;
    }
}
