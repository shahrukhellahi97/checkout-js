<?php
namespace BA\BasysOrders\Api\Data;

interface PaymentTypeInterface
{
    const SCHEMA = 'ba_basys_orders_payment_type';

    const DIVISION_ID = 'division_id';
    const PAYMENT_TYPE_ID = 'payment_type_id';
    const REFERENCE = 'reference';
    const DEFAULT = 'default';
    const METHOD = 'method';

    const KEYS = [
        PaymentTypeInterface::DIVISION_ID,
        PaymentTypeInterface::PAYMENT_TYPE_ID,
        PaymentTypeInterface::REFERENCE,
        PaymentTypeInterface::DEFAULT,
        PaymentTypeInterface::METHOD
    ];

    /**
     * Get payment type id
     *
     * @return int
     */
    public function getDivisionId();

    /**
     * Set division ID
     *
     * @param int $divisionId
     * @return self
     */
    public function setDivisionId($divisionId);

    /**
     * Get Payment Type ID
     *
     * @return string
     */
    public function getPaymentTypeId();

    /**
     * Set payment type id
     *
     * @param int $paymentId
     * @return self
     */
    public function setPaymentTypeId($paymentId);

    /**
     * Get payment reference
     *
     * @return string
     */
    public function getReference();

    /**
     * Set payment reference
     *
     * @param string $reference
     * @return self
     */
    public function setReference($reference);

    /**
     * Get payment method
     *
     * @see \BA\BasysOrders\Api\Data\PaymentTypeMethodMetadataInterface
     * @return string
     */
    public function getMethod();

    /**
     * Set payment type method
     *
     * @see \BA\BasysOrders\Api\Data\PaymentTypeMethodMetadataInterface
     * @param string $method
     * @return self
     */
    public function setMethod($method);

    /**
     * Is this the default payment method?
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

    /**
     * Get list of applicable UDFs if exists
     *
     * @return \BA\BasysOrders\Api\Data\UserDefinedFieldInterface[]|array|null
     */
    public function getUserDefinedFields();

    /**
     * Set user defined fields
     *
     * @param \BA\BasysOrders\Api\Data\UserDefinedFieldInterface[]|array $fields
     * @return self
     */
    public function setUserDefinedFields($fields);
}
