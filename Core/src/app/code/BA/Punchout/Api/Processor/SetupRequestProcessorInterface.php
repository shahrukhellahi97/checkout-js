<?php
namespace BA\Punchout\Api\Processor;

use BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface;
use BA\Punchout\Api\Data\DTOs\ResponseInterface;

interface SetupRequestProcessorInterface
{
    /**
     * Process a setup request received via Web API
     * 
     * @param \BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface $request 
     * @return \BA\Punchout\Api\Data\DTOs\ResponseInterface|null
     */
    public function process(SetupRequestInterface $request);
}