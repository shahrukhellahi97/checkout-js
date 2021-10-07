<?php
namespace BA\BasysCatalog\Api\Data;

interface SourceCodeInterface
{
    const SCHEMA_NAME = 'ba_basys_store_source_code';

    /**#@+
     * Constants defined for keys of data array
     */
    const ENTITY_ID = 'source_code_id';
    
    const NAME = 'name';

    const CATALOG_ID = 'catalog_id';
    /**#@-*/

    /**
     * Return source code id
     * 
     * @return int
     */
    public function getId();

    /**
     * Set source code id
     * 
     * @param int $id 
     * @return self
     */
    public function setId($id);

    /**
     * Get source code name
     * @return string
     */
    public function getName();

    /**
     * Set source code name
     * @param string $name 
     * @return self 
     */
    public function setName($name);

    /**
     * Get catalog id
     * @return int
     */
    public function getCatalogId();

    /**
     * Set catalog id
     * @param int $catalogId 
     * @return self
     */
    public function setCatalogId($catalogId);
}
