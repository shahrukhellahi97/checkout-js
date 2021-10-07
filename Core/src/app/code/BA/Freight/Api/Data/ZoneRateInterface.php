<?php
namespace BA\Freight\Api\Data;

interface ZoneRateInterface
{
    const SCHEMA_NAME = 'ba_freight_table_zone_rate';

    const TABLE_ID = 'table_id';
    const CODE_ID = 'code_id';
    const WEIGHT = 'weight';
    const VALUE = 'value';

    /**
     * Get Table ID
     *
     * @return int
     */
    public function getTableId();

    /**
     * Set Table ID
     *
     * @param int $tableId
     * @return self
     */
    public function setTableId($tableId);

    /**
     * Get Code ID
     *
     * @return int
     */
    public function getCodeId();

    /**
     * Set Code ID
     *
     * @param int $codeId
     * @return self
     */
    public function setCodeId($codeId);

    /**
     * Get Weight
     *
     * @return float
     */
    public function getWeight();

    /**
     * Set Weight
     *
     * @param float $weight
     * @return self
     */
    public function setWeight($weight);

    /**
     * Get Value
     *
     * @return float
     */
    public function getValue();

    /**
     * Set Value
     *
     * @param float $value
     * @return self
     */
    public function setValue($value);
}
