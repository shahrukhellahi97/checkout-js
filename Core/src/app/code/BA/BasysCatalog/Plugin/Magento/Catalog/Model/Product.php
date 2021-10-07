<?php
namespace BA\BasysCatalog\Plugin\Magento\Catalog\Model;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;

class Product
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

    public function afterGetIdentities(
        \Magento\Catalog\Model\Product $product,
        $result
    ) {
        $result[] = 'basys_' . $this->basysStoreManagement->getActiveCatalog()->getId();

        return $result;
    }
}