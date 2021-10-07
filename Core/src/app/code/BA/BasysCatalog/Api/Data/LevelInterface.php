<?php
namespace BA\BasysCatalog\Api\Data;

interface LevelInterface
{
    const SCHEMA_NAME = 'ba_basys_catalog_product_level';

    const BASYS_ID = 'basys_id';
    const LEVEL = 'level';
    const DUE = 'due';

    /**
     * @return int
     */
    public function getBasysId();

    /**
     * @param int $basysId 
     * @return self 
     */
    public function setBasysId($basysId);

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $level 
     * @return self
     */
    public function setLevel($level);

    /**
     * @return string
     */
    public function getDue();

    /**
     * @param string $dueDate 
     * @return self
     */
    public function setDue($dueDate);
}