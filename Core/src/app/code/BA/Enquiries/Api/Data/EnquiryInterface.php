<?php
namespace BA\Enquiries\Api\Data;

interface EnquiryInterface
{
    const EMAIL = 'email_address';
    const CONTACT_NAME = 'contact_name';
    const ADDITIONAL_FIELDS = 'additional_fields';
    const ITEMS = 'items';

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return self
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getContactName();

    /**
     * @param string $contactName
     * @return string
     */
    public function setContactName($contactName);

    /**
     * @return \BA\Enquiries\Api\Data\EnquiryFieldInterface
     */
    public function getAdditionalField($name);

    /**
     * @return \BA\Enquiries\Api\Data\EnquiryFieldInterface[]
     */
    public function getAdditionalFields();

    /**
     * @param \BA\Enquiries\Api\Data\EnquiryFieldInterface[] $fields
     * @return self
     */
    public function setAdditionalFields($fields);

    /**
     * @return \BA\Enquiries\Api\Data\EnquiryItemInterface[]
     */
    public function getItems();

    /**
     * @param \BA\Enquiries\Api\Data\EnquiryItemInterface[] $items
     * @return self
     */
    public function setItems($items);
}
