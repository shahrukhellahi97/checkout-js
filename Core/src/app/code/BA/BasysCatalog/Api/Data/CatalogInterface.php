<?php
namespace BA\BasysCatalog\Api\Data;

interface CatalogInterface
{
    const SCHEMA_NAME = 'ba_basys_store_catalog';

    /**#@+
     * Constants defined for keys of  data array
     */
    const ENTITY_ID = 'catalog_id';

    const NAME = 'name';

    const CURRENCY = 'currency';

    const DIVISION_ID = 'division_id';
    /**#@-*/

    /**
     * Return catalog id
     * 
     * @return int
     */
    public function getId();

    /**
     * Set catalog id
     * 
     * @param int $id 
     * @return self
     */
    public function setId($id);

    /**
     * Get catalog name
     * 
     * @return string
     */
    public function getName();

    /**
     * Set catalog name
     * 
     * @param string $name 
     * @return self
     */
    public function setName($name);

    /**
     * Get catalog currency
     * 
     * @return string
     */
    public function getCurrency();

    /**
     * Set catalog currency
     * 
     * @param string $currency 
     * @return self
     */
    public function setCurrency($currency);

    /**
     * Get division id
     * 
     * @return int
     */
    public function getDivisionId();

    /**
     * Set division id
     * 
     * @param int $divisionId 
     * @return self
     */
    public function setDivisionId($divisionId);
}