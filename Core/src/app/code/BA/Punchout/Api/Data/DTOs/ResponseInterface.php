<?php
namespace BA\Punchout\Api\Data\DTOs;

use BA\Punchout\Api\Data\DTOs\Types\StatusInterface;
use BA\Punchout\Api\Data\DTOs\Types\UrlInterface;

interface ResponseInterface
{
    const STATUS = 'status';
    const START_PAGE = 'start_page';
    
    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\StatusInterface 
     */
    function getStatus();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\StatusInterface $statusInterface 
     * @return self
     */
    function setStatus(StatusInterface $status);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\UrlInterface 
     */
    function getStartPage();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\UrlInterface|string $url 
     * @return self
     */
    function setStartPage(UrlInterface $url);
}