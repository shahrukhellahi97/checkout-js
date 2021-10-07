<?php
namespace BA\BasysCatalog\Cron\Queue;

use BA\BasysCatalog\Cron\JobInterface;
use BA\BasysCatalog\Import\Queue\QueueInterface;
use BA\BasysCatalog\Import\Queue\QueueListResultInterfaceFactory;
use BA\BasysCatalog\Import\Queue\QueueProcessorInterface;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Process implements JobInterface
{
    /**
     * @var \BA\BasysCatalog\Import\Queue\QueueInterface
     */
    protected $queue;

    /**
     * @var \BA\BasysCatalog\Import\Queue\QueueProcessorInterface
     */
    protected $queueProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        QueueInterface $queue,
        QueueProcessorInterface $queueProcessor
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->queue = $queue;
        $this->queueProcessor = $queueProcessor;
    }

    public function execute()
    {
        $listResult = $this->queue->list($this->searchCriteriaBuilder->create());

        if ($listResult->getSize() >= 1) {
            $this->queueProcessor->process($listResult, function ($basysProduct) {
                $this->queue->setStatus($basysProduct, QueueInterface::PROCESSED);
            });

            $this->queue->clean();
        }
    }
}