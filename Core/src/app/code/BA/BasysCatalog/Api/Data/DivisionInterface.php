<?php
namespace BA\BasysCatalog\Api\Data;


interface DivisionInterface
{
    const SCHEMA_NAME = 'ba_basys_store_division';

    /**#@+
     * Constants defined for keys of  data array
     */
    const ENTITY_ID = 'division_id';

    const NAME = 'name';
    /**#@-*/

    /**
     * Get division id
     * 
     * @return int
     */
    public function getId();

    /**
     * Set division id
     * 
     * @param int $divisionId 
     * @return self
     */
    public function setId($divisionId);

    /**
     * Get division name
     * 
     * @return string
     */
    public function getName();

    /**
     * Set division name
     * 
     * @param mixed $name 
     * @return self
     */
    public function setName($name);
}