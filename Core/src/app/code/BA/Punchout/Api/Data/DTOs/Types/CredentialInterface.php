<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface CredentialInterface extends AttributeCollectionInterface
{
    const IDENTITY = 'identity';
    
    /**
     * @return string|null
     */
    public function getIdentity();

    /**
     * @param string|null $identity 
     * @return self
     */
    public function setIdentity($identity);
}