<?php
namespace BA\BasysCatalog\Api\Data;

interface KeyGroupInterface
{
    const SCHEMA_NAME = 'ba_basys_store_keygroup';

    /**#@+
     * Constants defined for keys of  data array
     */
    const ENTITY_ID = 'keygroup_id';

    const NAME = 'name';

    const DIVISION_ID = 'division_id';
    /**#@-*/   

    /**
     * Get Entity ID
     * 
     * @return int
     */
    public function getId();

    /**
     * Set Entity ID
     * 
     * @param int $id 
     * @return self 
     */
    public function setId($id);

    /**
     * Get KeyGroup Name
     * 
     * @return string
     */
    public function getName();

    /**
     * Set KeyGroup Name
     * 
     * @param string $name 
     * @return self 
     */
    public function setName($name);

    /**
     * Get Division ID
     * 
     * @return int
     */
    public function getDivisionId();

    /**
     * Set Division ID
     * 
     * @param int $divisionId 
     * @return self 
     */
    public function setDivisionId($divisionId);
}