<?php
namespace BA\Basys\Webservices\Command;

use BA\Basys\Webservices\Response\HandlerInterface;
use Magento\Framework\MessageQueue\PublisherInterface;

class AsyncCommand implements CommandInterface
{
    /**
     * @var \BA\Basys\Webservices\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \BA\Basys\Webservices\Command\AsyncPublisher
     */
    protected $publisher;

    /**
     * @var \BA\Basys\Webservices\Command\AsyncCommandMessageFactoryInterface
     */
    protected $commandMessageFactory;

    /**
     * @var \BA\Basys\Webservices\Response\HandlerInterface|null
     */
    protected $handler;

    /**
     * @var string
     */
    protected $topicName;

    /**
     * @var \Magento\Framework\Amqp\Config
     */
    protected $config;

    public function __construct(
        CommandInterface $command,
        AsyncCommandMessageFactoryInterface $commandMessageFactory,
        AsyncPublisher $publisher,
        string $topicName = 'basys_command_queue',
        HandlerInterface $handler = null
    ) {
        $this->command = $command;
        $this->handler = $handler;
        $this->publisher = $publisher;
        $this->commandMessageFactory = $commandMessageFactory;
        $this->topicName = $topicName;
    }

    public function getName(): string
    {
        return $this->command->getName() . '_async';
    }

    public function execute(array $arguments, array $additional = [])
    {
        /** @var \BA\Basys\Webservices\Command\AsyncCommandMessageInterface $message */
        $message = $this->commandMessageFactory->create();

        $message->setCommand($this->command->getName())
            ->setTopic($this->topicName)
            ->setArguments(json_encode($arguments))
            ->setAdditional(json_encode($additional))
            ->setHandler(get_class($this->handler))
            ->setAttempts(0)
            ->setLevel(0)
            ->setPriority(AsyncCommandMessageInterface::PRIORITY_LOW)
            ->setDelay(0);

        $this->publisher->publish($message);
    }
}
