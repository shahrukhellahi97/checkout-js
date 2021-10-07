<?php
namespace BA\Basys\Webservices\Command;

use BA\Basys\Webservices\Command\DelayedDelivery\Infrastructure;
use PhpAmqpLib\Message\AMQPMessage;
use Magento\Framework\MessageQueue\EnvelopeFactory;
use Magento\Framework\MessageQueue\ExchangeRepository;
use Magento\Framework\MessageQueue\MessageEncoder;
use Magento\Framework\Amqp\Config as AmqpConfig;
use Magento\Framework\MessageQueue\Publisher\ConfigInterface as PublisherConfig;
use PhpAmqpLib\Wire\AMQPTable;

class AsyncPublisher
{
    /**
     * @var \Magento\Framework\MessageQueue\ExchangeRepository
     */
    protected $exchangeRepository;

    /**
     * @var \Magento\Framework\MessageQueue\EnvelopeFactory
     */
    protected $envelopeFactory;

    /**
     * @var \Magento\Framework\MessageQueue\MessageEncoder
     */
    protected $messageEncoder;

    /**
     * @var \Magento\Framework\Amqp\Config
     */
    protected $amqpConfig;

    /**
     * @var \Magento\Framework\MessageQueue\Publisher\ConfigInterface
     */
    protected $publisherConfig;

    /**
     * @var \BA\Basys\Webservices\Command\DelayedDelivery\Infrastructure
     */
    protected $infrastructure;

    public function __construct(
        ExchangeRepository $exchangeRepository,
        EnvelopeFactory $envelopeFactory,
        MessageEncoder $messageEncoder,
        AmqpConfig $amqpConfig,
        Infrastructure $infrastructure,
        PublisherConfig $publisherConfig
    ) {
        $this->exchangeRepository = $exchangeRepository;
        $this->envelopeFactory = $envelopeFactory;
        $this->infrastructure = $infrastructure;
        $this->messageEncoder = $messageEncoder;
        $this->amqpConfig = $amqpConfig;
        $this->publisherConfig = $publisherConfig;
    }

    public function publish(AsyncCommandMessageInterface $message)
    {
        $withDelay = max(0, $message->getDelay());
        $data = $this->messageEncoder->encode($message->getTopic(), $message);
        $envelope = $this->envelopeFactory->create(
            [
                'body' => $data,
                'properties' => [
                    'delivery_mode' => 2,
                    'message_id' => md5(uniqid($message->getTopic()))
                ]
            ]
        );

        $msg = new AMQPMessage($envelope->getBody(), $envelope->getProperties());
        $channel = $this->amqpConfig->getChannel();

        $defaultExchange = $this->publisherConfig
            ->getPublisher($message->getTopic())
            ->getConnection()
            ->getExchange();

        $this->infrastructure->build($channel);

        $startingLevel = 0;
        $key = $this->infrastructure->getRoutingKey(
            $withDelay,
            $message->getTopic(),
            $startingLevel
        );

        $wildcard = implode('.', array_map(function ($x) { 
            if (preg_match('/^[0-9]$/', $x)) {
                return '*';
            }
            
            return $x;
        }, explode('.', $key)));

        $channel->exchange_bind(
            $defaultExchange,
            Infrastructure::DLX,
            $wildcard,
            false,
            new AMQPTable([
                'x-routing-key' => $message->getTopic()
            ])
        );

        $channel->queue_bind(
            $message->getTopic(),
            $defaultExchange,
            $wildcard,
            false,
            new AMQPTable([
                'x-routing-key' => $message->getTopic()
            ])
        );

        $channel->basic_publish(
            $msg,
            $this->infrastructure->getLevel($startingLevel),
            $key
        );

        return null;
    }
}