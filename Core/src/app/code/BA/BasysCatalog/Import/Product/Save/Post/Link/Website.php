<?php
namespace BA\BasysCatalog\Import\Product\Save\Post\Link;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Import\Product\Save\Post\PostProcessorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnectionFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Store\Model\StoreManagerInterface;

class Website implements PostProcessorInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $catalogHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var array
     */
    private $processed = [];

    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        StoreManagerInterface $storeManager,
        Data $catalogHelper
    ) {
        $this->resourceConnection = $resourceConnectionFactory->create();
        $this->catalogHelper = $catalogHelper;
        $this->storeManager = $storeManager;
    }

    public function process(AdapterInterface $adapter, array $products)
    {
        $catalogs = [];

        foreach ($this->storeManager->getWebsites() as $website) {
            $catalogIds = $this->catalogHelper->getActiveCatalogIds($website->getId());

            foreach ($catalogIds as $catalogId) {
                if (!isset($catalogs[$catalogId])) {
                    $catalogs[$catalogId] = [];
                }
                
                $catalogs[$catalogId][] = $website->getId();
            }
        }
        
        $productIds = array_map(function ($product) {
            /** @var \Magento\Catalog\Model\Product $product */
            return (int) $product->getId();
        }, $products);

        $productSkus = array_map(function ($product) {
            /** @var \Magento\Catalog\Model\Product $product */
            return $product->getSku();
        }, $products);



        $adapter->delete(
            $adapter->getTableName('catalog_product_website'),
            // Not ideal, but eh
            'product_id IN ('. implode(', ', $productIds) .')'
        );

        $newConnection = $this->resourceConnection->getConnection();

        $select = $newConnection->select()
            ->from(
                ['ba' => $newConnection->getTableName(BasysProductInterface::SCHEMA . '_queue')],
                ['ba.catalog_id', 'ba.sku']
            )
            ->join(
                ['pr' => $newConnection->getTableName('catalog_product_entity')],
                'pr.sku = ba.sku',
                ['pr.row_id']
            );

        foreach ($newConnection->fetchAll($select) as $row) {
            if (isset($catalogs[$row['catalog_id']])) {
                foreach ($catalogs[$row['catalog_id']] as $websiteId) {
                    $this->linkToWebsite($adapter, $row['row_id'], $websiteId);
                }
            }
        }

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($products as $product) {
            $websiteIds = $product->getWebsiteIds();

            if ($product->getTypeId() == 'grouped') {
                $x = 's';
            }

            if (count($websiteIds) >= 1) {
                foreach ($websiteIds as $websiteId) {
                    $this->linkToWebsite($adapter, $product->getId(), $websiteId);
                }
            }
        }
    }

    private function linkToWebsite(AdapterInterface $adapter, $productId, $websiteId)
    {
        $key = implode('-', [
            $productId,
            $websiteId
        ]);

        if (!isset($this->processed[$key])) {
            $adapter->insertOnDuplicate(
                'catalog_product_website',
                [
                    'product_id' => $productId,
                    'website_id' => $websiteId
                ]
            );

            $this->processed[$key] = true;
        }
    }
}