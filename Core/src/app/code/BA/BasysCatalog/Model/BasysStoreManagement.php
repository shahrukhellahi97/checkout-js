<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use BA\BasysCatalog\Api\CatalogResolverInterface;
use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Model\ResourceModel\Catalog\CollectionFactory as CatalogCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class BasysStoreManagement implements BasysStoreManagementInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\Catalog\CatalogCollectionFactory
     */
    protected $catalogCollectionFactory;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $helper;

    /**
     * @var \BA\BasysCatalog\Model\SourceCodeFactory
     */
    protected $sourceCodeFactory;

    /**
     * @var \BA\BasysCatalog\Model\CatalogFactory
     */
    protected $catalogFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \BA\BasysCatalog\Api\Data\CatalogInterface
     */
    protected $activeCatalog;

    /**
     * @var \BA\BasysCatalog\Api\CatalogResolverInterface
     */
    protected $catalogResolver;

    /**
     * @var \BA\BasysCatalog\Api\Data\CatalogInterface[]|array
     */
    protected $catalogs;

    /**
     * @var \BA\BasysCatalog\Model\KeyGroupFactory
     */
    protected $keyGroupFactory;

    /**
     * @var \BA\BasysCatalog\Model\CustomerFactory
     */
    protected $customerFactory;

    public function __construct(
        CatalogCollectionFactory $catalogCollectionFactory,
        SourceCodeFactory $sourceCodeFactory,
        KeyGroupFactory $keyGroupFactory,
        CatalogFactory $catalogFactory,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        CatalogResolverInterface $catalogResolver,
        Data $helper
    ) {
        $this->sourceCodeFactory = $sourceCodeFactory;
        $this->catalogResolver = $catalogResolver;
        $this->catalogCollectionFactory = $catalogCollectionFactory;
        $this->catalogFactory = $catalogFactory;
        $this->storeManager = $storeManager;
        $this->keyGroupFactory = $keyGroupFactory;
        $this->customerFactory = $customerFactory;

        $this->helper = $helper;
    }

    public function getAvailableCurrencies()
    {
        $currencies = [];

        /** @var \BA\BasysCatalog\Api\Data\CatalogInterface $catalog */
        foreach ($this->getActiveCatalogs() as $catalog) {
            $currencies[$catalog->getCurrency()] = $catalog->getCurrency();
        }

        return $currencies;
    }

    public function getActiveCatalog()
    {
        if ($this->activeCatalog == null) {
            $catalogs = $this->getActiveCatalogs();
            $this->activeCatalog = $this->catalogResolver->resolve($catalogs);
        }

        return $this->activeCatalog;
    }

    /**
     *
     * @todo Grab source code from store config.
     * @param string|null $storeCode
     * @return \BA\BasysCatalog\Api\Data\SourceCodeInterface
     */
    public function getActiveSourceCode($storeCode = null)
    {
        $catalogId = $this->getActiveCatalog()->getId();

        if ($sourceCodeId = $this->helper->getSourceCodeId($catalogId)) {
            return $this->sourceCodeFactory->create()
                ->setId($sourceCodeId);
        }
    }

    public function getActiveKeyGroup($storeCode = null)
    {
        $catalogId = $this->getActiveCatalog()->getId();

        if ($keygroup = $this->helper->getKeyGroupId($catalogId)) {
            return $this->keyGroupFactory->create()
                ->setId($keygroup);
        }
    }

    public function getDefaultCustomerId()
    {
        $catalogId = $this->getActiveCatalog()->getId();
        
        if ($customer = $this->helper->getDefaultCustomerId($catalogId)) {
            return $customer;
        }
    }

    public function getActiveCatalogs($storeCode = null)
    {
        $ids = $this->helper->getActiveCatalogIds($storeCode);

        if (count($ids) >= 1 && $this->catalogs == null) {
            $collection = $this->catalogCollectionFactory->create();
            $catalogs = $collection->addFieldToFilter(CatalogInterface::ENTITY_ID, ['in' => $ids])->load();

            $this->catalogs = $catalogs->getItems();
        }

        return $this->catalogs ?? [];
    }
}
