<?php
namespace BA\BasysCatalog\Plugin\Magento\Catalog\Pricing\Render;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;

class FinalPriceBox
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    public function __construct(
        BasysStoreManagementInterface $basysStoreManagement
    ) {
        $this->basysStoreManagement = $basysStoreManagement;
    }

    public function afterGetCacheKey(
        \Magento\Catalog\Pricing\Render\FinalPriceBox $subject,
        $result
    ) {
        return $result . 'basys_' . $this->basysStoreManagement->getActiveCatalog()->getId();
    }

    public function afterGetCacheKeyInfo(
        \Magento\Catalog\Pricing\Render\FinalPriceBox $subject,
        $result
    ) {
        $result['basys_catalog_id'] = $this->basysStoreManagement->getActiveCatalog()->getId();

        return $result;
    }
}