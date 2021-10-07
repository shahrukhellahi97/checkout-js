<?php
namespace BA\BasysOrders\Api\Data;

interface UserDefinedFieldOptionInterface
{
    const SCHEMA = 'ba_basys_orders_udf_option';

    const DIVISION_ID = 'division_id';
    const SEQUENCE_ID = 'sequence_id';
    const OPTION_ID = 'option_id';
    const VALUE = 'value';
    const DEFAULT = 'default';

    const KEYS = [
        self::DIVISION_ID,
        self::SEQUENCE_ID,
        self::OPTION_ID,
        self::VALUE,
        self::DEFAULT
    ];

    /**
     * Get payment type id
     *
     * @return int
     */
    public function getDivisionId();

    /**
     * Get division ID
     *
     * @param int $divisionId
     * @return self
     */
    public function setDivisionId($divisionId);

    /**
     * Get UDF Sequence No
     *
     * @return int
     */
    public function getSequenceNo();

    /**
     * Set sequence No
     *
     * @param int $sequenceNo
     * @return self
     */
    public function setSequenceNo($sequenceNo);

    /**
     * Get UDF Option ID
     *
     * @return int
     */
    public function getOptionId();

    /**
     * Set UDF Option ID
     *
     * @param int $sequenceNo
     * @return self
     */
    public function setOptionId($optionId);

    /**
     * Get option value
     *
     * @return string
     */
    public function getValue();

    /**
     * Set value
     *
     * @param string $value
     * @return self
     */
    public function setValue($value);

    /**
     * Is default?
     *
     * @return bool
     */
    public function getDefault();

    /**
     * Set is default
     *
     * @param bool $default
     * @return self
     */
    public function setDefault($default);
}
