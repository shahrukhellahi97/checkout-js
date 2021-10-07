<?php
namespace BA\BasysOrders\Api\Data;

interface UserDefinedFieldInterface
{
    const SCHEMA = 'ba_basys_orders_udf';

    const DIVISION_ID = 'division_id';
    const SEQUENCE_ID = 'sequence_id';
    const CAPTION = 'caption';
    const RULE = 'rule';
    const UPPERCASE = 'UPPERCASE';

    const KEYS = [
        self::DIVISION_ID,
        self::SEQUENCE_ID,
        self::CAPTION,
        self::RULE,
        self::UPPERCASE
    ];

    /**
     * Get payment type id
     *
     * @return int
     */
    public function getDivisionId();

    /**
     * Set division ID
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
     * Set sequence no
     *
     * @param int $sequenceNo
     * @return self
     */
    public function setSequenceNo($sequenceNo);

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption();

    /**
     * Set Caption
     *
     * @param string $caption
     * @return self
     */
    public function setCaption($caption);

    /**
     * Get rule
     *
     * @return string
     */
    public function getRule();

    /**
     * Set rule
     *
     * @param string $rule
     * @return self
     */
    public function setRule($rule);

    /**
     * Get uppercase
     *
     * @return bool
     */
    public function getUppercase();

    /**
     * Set uppercase
     *
     * @param bool $uppercase
     * @return self
     */
    public function setUppercase($uppercase);

    /**
     * Get Value
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set Value
     *
     * @param mixed $value
     * @return mixed
     */
    public function setValue($value);

    /**
     * Get UDF options
     *
     * @return \BA\BasysOrders\Api\Data\UserDefinedFieldOptionInterface[]|array
     */
    public function getOptions();

    /**
     * Set UDF options
     *
     * @param \BA\BasysOrders\Api\Data\UserDefinedFieldOptionInterface[]|array $options
     * @return self
     */
    public function setOptions($options);

    /**
     * Add option to UDF
     *
     * @param string $value
     * @param bool $isDefault
     * @return void
     */
    public function addOption($value, $isDefault = false);
}
