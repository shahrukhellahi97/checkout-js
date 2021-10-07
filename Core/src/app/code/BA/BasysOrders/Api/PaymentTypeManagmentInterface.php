<?php
namespace BA\BasysOrders\Api;

use BA\BasysOrders\Api\Data\PaymentTypeInterface;

interface PaymentTypeManagmentInterface
{
    /**
     * Get all available payment types for this division
     *
     * @param int|null $divisionId
     * @param bool $fromWebservices
     * @return \BA\BasysOrders\Api\Data\PaymentTypeInterface[]|array|null
     */
    public function getAllPaymentTypes($divisionId = null, $fromWebservices = false);

    /**
     * Get all visible payment types
     *
     * @param string|array|null $paymentMethod
     * @return \BA\BasysOrders\Api\Data\PaymentTypeInterface[]|array|null
     */
    public function getVisiblePaymentTypes($paymentMethod = null);
    /**
     * Save payment types
     *
     * @param \BA\BasysOrders\Api\Data\PaymentTypeInterface[]|\BA\BasysOrders\Api\Data\PaymentTypeInterface|array $type
     * @return mixed
     */
    public function save($type);
}
