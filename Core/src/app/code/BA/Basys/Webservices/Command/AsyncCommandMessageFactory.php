<?php
namespace BA\Basys\Webservices\Command;

use Magento\Framework\ObjectManagerInterface;

class AsyncCommandMessageFactory implements AsyncCommandMessageFactoryInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(array $data = []): AsyncCommandMessageInterface
    {
        return $this->objectManager->create(AsyncCommandMessage::class, $data);
    }
}