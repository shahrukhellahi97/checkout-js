<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface AddressInterface extends AttributeCollectionInterface
{
    const DELIVER_TO = 'deliver_to';
    
    const STREET = 'street';
    
    const CITY = 'city';
    
    const STATE = 'state';
    
    const POSTCODE = 'postcode';
    
    const COUNTRY = 'country';
    

    /**
     * Get deliver to
     * @return string 
     */
    public function getDeliverTo();

    /**
     * Set deliver to
     * 
     * @param string $value 
     * @return self
     */
    public function setDeliverTo(string $value);

    /**
     * Get street
     * 
     * @return string 
     */
    public function getStreet();

    /**
     * Set street 
     * @param string $value 
     * @return self 
     */
    public function setStreet(string $value);
    
    /**
     * Get city
     * 
     * @return string|null
     */
    public function getCity();

    /**
     * Set city
     * 
     * @param string $value 
     * @return self 
     */
    public function setCity(string $value);

    /**
     * Get state
     * 
     * @return string|null
     */
    public function getState();

    /**
     * Set state
     * 
     * @param string $value 
     * @return self 
     */
    public function setState(string $value);

    /**
     * Get postal code
     * 
     * @return string|null
     */
    public function getPostalCode();

    /**
     * Set postal code
     * 
     * @param string $value 
     * @return self 
     */
    public function setPostalCode(string $value);

    /**
     * Get country value
     * 
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country value
     * 
     * @param string $country 
     * @return self 
     */
    public function setCountry(string $country);
}