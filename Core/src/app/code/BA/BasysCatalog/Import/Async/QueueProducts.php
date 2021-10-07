<?php
namespace BA\BasysCatalog\Import\Async;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\BasysCatalog\Import\ProductQueueProcessorInterface;

class QueueProducts implements HandlerInterface
{
    /**
     * @var \BA\BasysCatalog\Import\ProductQueueProcessorInterface
     */
    protected $productQueueProcessor;

    public function __construct(
        ProductQueueProcessorInterface $productQueueProcessor
    ) {
        $this->productQueueProcessor = $productQueueProcessor;
    }

    public function handle($response, array $additional = [])
    {
        $this->productQueueProcessor->process($additional['division_id'], $response);
    }
}
