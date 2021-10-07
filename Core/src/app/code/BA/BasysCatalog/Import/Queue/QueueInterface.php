<?php
namespace BA\BasysCatalog\Import\Queue;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface QueueInterface
{
    const QUEUED = 0;
    const PROCESSED = 1;
    const PROCESSING = 2;

    /**
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $product
     * @return void
     */
    public function add(BasysProductInterface $product);

    /**
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $product
     * @return void
     */
    public function remove(BasysProductInterface $product);

    /**
     * Set queue item status
     *
     * @param \BA\BasysCatalog\Api\Data\BasysProductInterface $product
     * @param int $status
     * @return void
     */
    public function setStatus(BasysProductInterface $product, int $status);

    /**
     * Get items from queue
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \BA\BasysCatalog\Import\Queue\QueueListResultInterface
     */
    public function list(SearchCriteriaInterface $searchCriteria);

    /**
     * Clean the queue
     *
     * @return void
     */
    public function clean();
}
