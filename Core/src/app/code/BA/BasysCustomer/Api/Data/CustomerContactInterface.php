<?php
namespace BA\BasysCustomer\Api\Data;

interface CustomerContactInterface
{
    const SCHEMA = 'ba_basys_customer_contact';

    const CONTACT_ID = 'contact_id';
    const EMAIL = 'email';
    const CURRENCY = 'currency';
    const DIVISION_ID = 'division_id';
    const USER_TYPE_ID = 'user_type_id';

    /**
     * Get contact id
     *
     * @return int
     */
    public function getContactId();

    /**
     * Set contact id
     *
     * @param int $contactId
     * @return self
     */
    public function setContactId($contactId);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set email address
     *
     * @param string $emailAddress
     * @return self
     */
    public function setEmail($emailAddress);

    /**
     * Get currency code
     *
     * @return string
     */
    public function getCurrency();

    /**
     * Set currency code
     *
     * @param string $currency
     * @return self
     */
    public function setCurrency($currency);

    /**
     * Get division id
     *
     * @return int
     */
    public function getDivisionId();

    /**
     * Set division id
     *
     * @param int $divisionId
     * @return self
     */
    public function setDivisionId($divisionId);

    /**
     * Get user-type id
     *
     * @return int
     */
    public function getUserTypeId();

    /**
     * Set user-type id
     *
     * @param int $userTypeId
     * @return self
     */
    public function setUserTypeId($userTypeId);
}
