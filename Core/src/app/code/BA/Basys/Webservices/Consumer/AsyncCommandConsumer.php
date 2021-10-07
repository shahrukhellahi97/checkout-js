<?php
namespace BA\Basys\Webservices\Consumer;

use BA\Basys\Webservices\Command\AsyncCommandMessageFactoryInterface;
use BA\Basys\Webservices\Command\AsyncCommandMessageInterface;
use BA\Basys\Webservices\Command\AsyncPublisher;
use BA\Basys\Webservices\Command\CommandPoolInterface;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;

class AsyncCommandConsumer implements AsyncCommandConsumerInterface
{
    const MAX_ATTEMPTS = 3;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \BA\Basys\Webservices\Command\AsyncPublisher
     */
    protected $publisher;

    /**
     * @var \BA\Basys\Webservices\Command\AsyncCommandMessageFactoryInterface
     */
    protected $commandMessageFactory;

    public function __construct(
        ObjectManagerInterface $objectManager,
        AsyncCommandMessageFactoryInterface $commandMessageFactory,
        CommandPoolInterface $commandPool,
        LoggerInterface $logger,
        AsyncPublisher $publisher
    ) {
        $this->commandMessageFactory = $commandMessageFactory;
        $this->publisher = $publisher;
        $this->objectManager = $objectManager;
        $this->commandPool = $commandPool;
        $this->logger = $logger;
    }

    /**
     * Nothing special here
     *
     * @param \BA\Basys\Webservices\Command\AsyncCommandMessageInterface $message
     * @return void
     */
    public function process(AsyncCommandMessageInterface $message)
    {
        $command = $this->commandPool->get($message->getCommand());

        /** @var \BA\Basys\Webservices\Response\HandlerInterface $handler */
        $handler = $this->objectManager->get($message->getHandler());

        $arguments  = json_decode($message->getArguments(), true);
        $additional = json_decode($message->getAdditional(), true);

        $this->logger->debug('handling async command', [
            'method'    => $message->getCommand(),
            'handler'   => $message->getHandler(),
            'arguments' => $arguments,
            'additional' => $additional,
        ]);

        try {
            $handler->handle(
                $command->execute($arguments, $additional),
                $additional
            );
        } catch (\SoapFault $e) {
            if ($message->getPriority() > AsyncCommandMessageInterface::PRIORITY_NONE) {
                if ($this->applyDelay($message)) {
                    $this->publisher->publish($message);
                } else {
                    $error = sprintf('Failed to consume message after %s attempts', self::MAX_ATTEMPTS * ( $message->getLevel() + 1));
                    $this->logger->error($error, [
                        'exception' => [
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'message' => $e->getMessage()
                        ],
                        'message' => $message
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to consume message', [
                'exception' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ],
                'message' => $message
            ]);
        }
    }

    private function applyDelay(AsyncCommandMessageInterface &$message): bool
    {
        if ($message->getAttempts() <= self::MAX_ATTEMPTS) {
            $message->setAttempts($message->getAttempts() + 1);
            $message->setDelay(0);

            return true;
        } else {
            $currentLevel = $message->getLevel() + 1;

            if ($wait = $this->getWaitTimes($currentLevel)) {
                $message->setAttempts(0);
                $message->setLevel($currentLevel);
                $message->setDelay($wait);

                return true;
            }
        }

        return false;
    }

    private function getWaitTimes($level) 
    {
        $times = [
            1 => 10,
            2 => 30,
            4 => 60,
            5 => 300,
            6 => 600,
            7 => 3600,
        ];

        if (isset($times[$level])) {
            return $times[$level];
        }

        return null;
    }
}
