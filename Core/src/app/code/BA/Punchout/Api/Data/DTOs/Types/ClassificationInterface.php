<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface ClassificationInterface
{
    const DOMAIN = 'domain';
    
    const VALUE = 'value';
    
    /**
     * @return string
     */
    public function getDomain();

    /**
     * @param string $value 
     * @return self 
     */
    public function setDomain($value);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value 
     * @return self 
     */
    public function setValue($value);
}