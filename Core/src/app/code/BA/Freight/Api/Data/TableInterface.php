<?php
namespace BA\Freight\Api\Data;

interface TableInterface
{
    const SCHEMA_NAME = 'ba_freight_table';
    const TABLE_ID = 'table_id';
    const NAME = 'name';

    /**
     * Get Table ID
     *
     * @return int
     */
    public function getId();

    /**
     * Set Table ID
     *
     * @param int $tableId
     * @return self
     */
    public function setId($tableId);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name);

    /**
     * Get rate for rate
     * 
     * @param string $countryId
     * @param float $weight
     * @return \BA\Freight\Api\Data\ZoneRateInterface
     */
    public function getRate($countryId, $weight = 0.00);
}
