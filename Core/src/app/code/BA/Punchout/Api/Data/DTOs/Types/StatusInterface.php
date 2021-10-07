<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface StatusInterface
{
    const CODE = 'code';

    const TEXT = 'text';

    /**
     * HTTP Status Code.
     * 
     * @var \BA\Punchout\Api\getCode
     * @return int
     */
    public function getCode();

    /**
     * @param int $code 
     * @return self
     */
    public function setCode(int $code);

    /**
     * Get Error context message
     * 
     * @return string 
     */
    public function getText();

    /**
     * @param string $code 
     * @return self
     */
    public function setText(string $code);

    /**
     * Set status message code
     * 
     * @param int $status 
     * @param string|null $message 
     * @return self
     */
    public function setStatus(int $status, string $message = null);
}