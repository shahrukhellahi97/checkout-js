<?php
namespace BA\Punchout\Api\Data\DTOs\Types;

interface UrlInterface
{
    const URL = 'url';
    
    /**
     * Get URL
     * 
     * @return string|null
     */
    public function getUrl();

    /**
     * Set URL
     * 
     * @param mixed $url 
     * @return self
     */
    public function setUrl($url);
}