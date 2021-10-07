<?php
namespace BA\Punchout\Api\Data\DTOs;

use BA\Punchout\Api\Data\DTOs\Types\HeaderInterface;

interface RequestInterface 
{
    const PAYLOAD_ID = 'payload_id';

    const TIMESTAMP = 'timestamp';

    const HEADER = 'header';
    
    /**
     * @return string
     */
    public function getPayloadId();

    /**
     * @param string $payload 
     * @return self
     */
    public function setPayloadId(string $payload);

    /**
     * @return string
     */
    public function getTimestamp();

    /**
     * @param string $timestamp 
     * @return self
     */
    public function setTimestamp(string $timestamp);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\HeaderInterface 
     */
    public function getHeader();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\HeaderInterface $header 
     * @return self
     */
    public function setHeader(HeaderInterface $header);
}