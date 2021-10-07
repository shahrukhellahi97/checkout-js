<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface HeaderInterface 
{
    const TO     = 'to';

    const FROM   = 'from';
    
    const SENDER = 'sender';

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface 
     */
    public function getTo();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface|null $credential 
     * @return self
     */
    public function setTo($credential);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface
     */
    public function getFrom();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface|null $credential 
     * @return self
     */
    public function setFrom($credential);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface 
     */
    public function getSender();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface|null $credential 
     * @return self
     */
    public function setSender($credential);
}