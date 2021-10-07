<?php
namespace BA\Freight\Api\Data;

interface ZoneInterface
{
    const SCHEMA_NAME = 'ba_freight_table_zone';
        
    const TABLE_ID = 'table_id';
    const COUNTRY_ID = 'country_id';
    const CODE_ID = 'code_id';

    /**
     * Get Table ID
     *
     * @return int
     */
    public function getTableId();

    /**
     * Set Table ID
     *
     * @param mixed $id
     * @return self
     */
    public function setTableId($id);

    /**
     * Get 2-character country code
     *
     * @return string
     */
    public function getCountryId();

    /**
     * Set 2-character country code
     *
     * @param string $countryCode
     * @return self
     */
    public function setCountryId($countryCode);

    /**
     * Get code id
     *
     * @return int
     */
    public function getCodeId();

    /**
     * Set code ID
     *
     * @param int $id
     * @return self
     */
    public function setCodeId($id);
}
