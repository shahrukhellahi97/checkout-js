<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface AttributeInterface
{
    const KEY   = 'key';
    
    const VALUE = 'value';
    
    /**
     * @return string 
     */
    public function getKey();

    /**
     * @param string $keyName 
     * @return self
     */
    public function setKey(string $keyName);

    /**
     * @return string 
     */
    public function getValue();

    /**
     * @param string $value 
     * @return self
     */
    public function setValue(string $value);
}