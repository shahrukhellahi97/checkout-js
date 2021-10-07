<?php
namespace BA\BasysCatalog\Api\Data;

interface CustomerInterface
{
    const SCHEMA_NAME = 'ba_basys_store_customer';

    /**#@+
     * Constants defined for keys of  data array
     */
    const CUSTOMER_ID = 'customer_id';
    const DIVISION_ID = 'division_id';
    const SOURCE_CODE_ID = 'source_code_id';
    const NAME = 'name';
    const CUSTOMER_CONTACT_ID = 'customer_contact_id';
    const SALES_PERSON_ID = 'sales_person_id';
    /**#@-*/

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return self
     */
    public function setCustomerId($customerId);

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
     * Get source code id
     *
     * @return int
     */
    public function getSourceCodeId();

    /**
     * Set source code id
     *
     * @param int $sourceCodeId
     * @return self
     */
    public function setSourceCodeId($sourceCodeId);

    /**
     * Get name
     *
     * @return int
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
     * Get customer contact id
     *
     * @return int
     */
    public function getCustomerContactId();

    /**
     * Set customer contact id
     *
     * @param int $contactId
     * @return self
     */
    public function setCustomerContactId($contactId);

    /**
     * Get sales person
     *
     * @return int
     */
    public function getSalesPersonId();

    /**
     * Set sales person id
     *
     * @param int $salesPersonId
     * @return self
     */
    public function setSalesPersonId($salesPersonId);
}
