<?php
namespace BA\BasysCatalog\Import\Queue;

interface QueueListResultInterface
{
    /**
     * @return \BA\BasysCatalog\Api\Data\BasysProductInterface[]|array
     */
    public function getAll();

    /**
     * Get quantity
     *
     * @return int
     */
    public function getSize();
}
