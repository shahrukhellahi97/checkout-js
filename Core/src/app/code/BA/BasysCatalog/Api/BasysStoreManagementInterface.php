<?php
namespace BA\BasysCatalog\Api;

interface BasysStoreManagementInterface
{
    /**
     * Return currencies for enabled catalogs
     * @return array Format: array('<value>', ...)
     */
    public function getAvailableCurrencies();

    /**
     * Return the current active catalog
     * @return \BA\BasysCatalog\Api\Data\CatalogInterface
     */
    public function getActiveCatalog();

    /**
     * Return enabled catalogs
     * @param string|null $storeCode
     * @return \BA\BasysCatalog\Api\Data\CatalogInterface[]|array
     */
    public function getActiveCatalogs($storeCode = null);

    /**
     * @param string|null $storeCode
     * @return \BA\BasysCatalog\Api\Data\SourceCodeInterface
     */
    public function getActiveSourceCode($storeCode = null);

    /**
     * @param string|null $storeCode
     * @return \BA\BasysCatalog\Api\Data\KeyGroupInterface
     */
    public function getActiveKeyGroup($storeCode = null);

    /**
     * @return int
     */
    public function getDefaultCustomerId();
}
