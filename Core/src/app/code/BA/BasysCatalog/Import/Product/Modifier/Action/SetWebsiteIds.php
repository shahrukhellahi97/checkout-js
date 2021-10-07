<?php
namespace BA\BasysCatalog\Import\Product\Modifier\Action;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Import\Product\Modifier\ModifierInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Store\Model\ScopeInterface;

class SetWebsiteIds implements ModifierInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var int[]
     */
    protected $catalogs;

    /**
     * @var string[]
     */
    protected $linked = [];

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $catalogHelper;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \BA\BasysCatalog\Helper\Data $catalogHelper
    ) {
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->config = $config;
        $this->catalogHelper = $catalogHelper;
    }

    public function apply(ProductInterface $product, BasysProductInterface $basysProduct = null): ProductInterface
    {
        $websiteIds = [];

        if ($basysProduct != null) {
            $websiteIds = $this->getWebsiteIds($basysProduct->getCatalogId());

            /** @var \Magento\Catalog\Model\Product $product */
            $product->setWebsiteIds($websiteIds);
            $this->linked[$product->getSku()] = $websiteIds;

            $product->setStatus(1);
        }

        return $product;
    }

    protected function getAssociatedWebsiteIds(Product $product)
    {
        $websiteIds = [];

        /** @var \Magento\Catalog\Api\Data\ProductLinkInterface $link */
        foreach ($product->getProductLinks() as $link) {
            // phpcs:disable
            $websiteIds = array_merge($websiteIds, $this->linked[$link->getLinkedProductSku()]);
            // phpcs:enable
        }

        return $websiteIds;
    }

    protected function getWebsiteIds($catalogId)
    {
        if ($this->catalogs == null) {
            $websites = $this->storeManager->getWebsites();

            /** @var \Magento\Store\Api\Data\WebsiteInterface $website */
            foreach ($websites as $website) {
                $x = [
                    $website->getCode(),
                    $website->getId()
                ];

                try {
                    $catalogs = $this->catalogHelper->getActiveCatalogIds(
                        $website->getId()
                    );

                    foreach ($catalogs as $catalog) {
                        $this->catalogs[$catalog][] = $website->getId();
                    }
                } catch (\Exception $e) {
                    // Store doesn't really exist, so move on.
                    continue;
                }
            }
        }

        return isset($this->catalogs[$catalogId]) ? $this->catalogs[$catalogId] : [];
    }
}
