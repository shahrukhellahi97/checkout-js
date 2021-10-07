<?php
namespace BA\BasysCatalog\Import\Queue;

interface QueueProcessorInterface
{
    /**
     * Process a queue list
     *
     * @param \BA\BasysCatalog\Import\Queue\QueueListResultInterface $queue
     * @param callable|null $callback
     * @return void
     */
    public function process(QueueListResultInterface $queue, callable $callback = null);
}
