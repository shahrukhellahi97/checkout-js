<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface ContactInterface extends AttributeCollectionInterface
{
    const NAME  = 'name';

    const EMAIL = 'email';

    const CURRENCY = 'currency';
    
    /**
     * @return string 
     */
    function getName();

    /**
     * @param string $name 
     * @return self
     */
    public function setName(string $name);

    /**
     * @return string 
     */
    public function getEmail();

    /**
     * @param string|null $email 
     * @return self
     */
    public function setEmail($email);

    /**
     * @return string 
     */
    public function getCurrency();

    /**
     * @param string $value 
     * @return self
     */
    public function setCurrency(string $value);
}