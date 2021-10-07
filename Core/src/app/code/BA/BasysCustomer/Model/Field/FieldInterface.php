<?php
namespace BA\BasysCustomer\Model\Field;

interface FieldInterface
{
    /**
     * Get label for presentation
     * 
     * @return string
     */
    public function getLabel();

    /**
     * Get EAV attribute code
     * 
     * @return string
     */
    public function getCode();

    /**
     * Get attribute value
     * 
     * @return mixed
     */
    public function getValue();

    /**
     * Check if this field is required
     * 
     * @return bool
     */
    public function getIsRequired();
}