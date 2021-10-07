<?php
namespace BA\BasysCatalog\Api\Data;

interface LookupInterface
{
    const SCHEMA_NAME = 'ba_basys_lookup_table';

    /**#@+
     * Constants defined for keys of  data array
     */
    const DIVISION_ID = 'division_id';

    const WEBSITE_ID = 'website_id';

    const CATALOG_ID = 'catalog_id';

    const STORE_ID = 'store_id';

    const SOURCE_ID = 'source_id';

    const STORE_VIEW_ID = 'store_view_id';

    const ROOT_CATEGORY_ID = 'root_category_id';


    /**#@-*/

    /**
     * Return division_id
     * 
     * @return int
     */
    public function getDivisionId();

    /**
     * Set division_id
     * 
     * @param int $id 
     * @return self
     */
    public function setDivisionId($division_id);

    /**
     * Get website_id name
     * 
     * @return int
     */
    public function getWebsiteId();

    /**
     * Set website id
     * 
     * @param int $websiteId 
     * @return int
     */
    public function setWebsiteId($websiteId);

    /**
     * Get catalog_id name
     * 
     * @return int
     */
    public function getCatalogId();

    /**
     * Set catalog id
     * 
     * @param int $catalogId 
     * @return int
     */
    public function setCatalogId($catalogId);

    /**
     * Get store Id
     * 
     * @return int
     */
    public function getStoreId();

    /**
     * Set storeId
     * 
     * @param string $storeId
     * @return int
     */
    public function setStoreId($storeId);

    /**
     * Get source Id
     * 
     * @return int
     */
    public function getSourceId();

    /**
     * Set sourceId
     * 
     * @param string $sourceId
     * @return int
     */
    public function setSourceId($sourceId);

    /**
     * Get store view id
     * 
     * @return int
     */
    public function getStoreViewId();

    /**
     * Set store view id
     * 
     * @param int $storeViewId 
     * @return self
     */
    public function setStoreViewId($storeViewId);

    /**
     * Get root category id
     * 
     * @return int
     */
    public function getRootCategoryId();

    /**
     * Set root category id
     * 
     * @param int $rootCategoryId 
     * @return self
     */
    public function setRootCategoryId($rootCategoryId);
}