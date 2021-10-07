<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface AttributeCollectionInterface
{
    const ATTRIBUTES = 'attributes';

    /**
     * Get list of attributes
     * 
     * @return \BA\Punchout\Api\Data\DTOs\Types\AttributeInterface[]
     */
    public function getAttributes();

    /**
     * Set attributes
     * 
     * @param \BA\Punchout\Api\Data\DTOs\Types\AttributeInterface[] $attributes 
     * @return self 
     */
    public function setAttributes(array $attributes);

    /**
     * Get key by name
     * 
     * @param string $keyName 
     * @return null|\BA\Punchout\Api\Data\DTOs\Types\AttributeInterface
     */
    public function getAttributeByKey(string $keyName);
}