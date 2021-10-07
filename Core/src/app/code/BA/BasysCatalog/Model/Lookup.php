<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\LookupInterface;
use BA\BasysCatalog\Model\ResourceModel\Lookup as ResourceModelLookup;
use Magento\Framework\Model\AbstractModel;

class Lookup extends AbstractModel implements LookupInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelLookup::class);
    }

    public function getDivisionId()
    {
        return $this->getData(LookupInterface::DIVISION_ID);
    }

    public function setDivisionId($division_id)
    {
        return $this->setData(LookupInterface::DIVISION_ID, $division_id);
    }

    public function getWebsiteId()
    {
        return $this->getData(LookupInterface::WEBSITE_ID);
    }

    public function setWebsiteId($websiteId)
    {
        return $this->setData(LookupInterface::WEBSITE_ID, $websiteId);
    }

    public function getCatalogId()
    {
        return $this->getData(LookupInterface::CATALOG_ID);
    }

    public function setCatalogId($catalogId)
    {
        return $this->setData(LookupInterface::CATALOG_ID, $catalogId);
    }

    public function getStoreId()
    {
        return $this->getData(LookupInterface::STORE_ID);
    }

    public function setStoreId($storeId)
    {
        return $this->setData(LookupInterface::STORE_ID, $storeId);
    }

    public function getSourceId()
    {
        return $this->getData(LookupInterface::SOURCE_ID);
    }

    public function setSourceId($storeId)
    {
        return $this->setData(LookupInterface::SOURCE_ID, $storeId);
    }

    public function getStoreViewId()
    {
        return $this->getData(LookupInterface::STORE_VIEW_ID);
    }

    public function setStoreViewId($storeViewId)
    {
        return $this->setData(LookupInterface::STORE_VIEW_ID, $storeViewId);
    }

    public function getRootCategoryId()
    {
        return $this->getData(LookupInterface::ROOT_CATEGORY_ID);
    }

    public function setRootCategoryId($rootCategoryId)
    {
        return $this->setData(LookupInterface::ROOT_CATEGORY_ID, $rootCategoryId);
    }

}