<?php
namespace BA\Basys\Framework\Amqp;

use Magento\Framework\Amqp\Config;
use Magento\Framework\MessageQueue\ConnectionLostException;
use Magento\Framework\MessageQueue\EnvelopeFactory;
use PhpAmqpLib\Exception\AMQPProtocolConnectionException;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class Queue extends \Magento\Framework\Amqp\Queue
{
   /**
    * @var \Magento\Framework\Amqp\Config
    */
    private $_amqpConfig;

    /**
     * @var string
     */
    private $_queueName;

    /**
     * @var \Magento\Framework\MessageQueue\EnvelopeFactory
     */
    private $_envelopeFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $_logger;

    public function __construct(
        Config $amqpConfig,
        EnvelopeFactory $envelopeFactory,
        $queueName,
        LoggerInterface $logger
    ) {
        parent::__construct($amqpConfig, $envelopeFactory, $queueName, $logger);

        $this->_amqpConfig = $amqpConfig;
        $this->_queueName = $queueName;
        $this->_envelopeFactory = $envelopeFactory;
        $this->_logger = $logger;
    }

    /**
     * @inheritdoc
     * @since 103.0.0
     */
    public function subscribe($callback)
    {
        $callbackConverter = function (AMQPMessage $message) use ($callback) {
            // @codingStandardsIgnoreStart
            $x = $message->get_properties();
            $properties = array_merge(
                $message->get_properties(),
                [
                    'topic_name' => $this->getTopicName(
                        $message->delivery_info['routing_key']
                    ),
                    'delivery_tag' => $message->delivery_info['delivery_tag'],
                ]
            );
            // @codingStandardsIgnoreEnd
            $envelope = $this->_envelopeFactory->create(['body' => $message->body, 'properties' => $properties]);

            if ($callback instanceof \Closure) {
                $callback($envelope);
            } else {
                call_user_func($callback, $envelope);
            }
        };

        $channel = $this->_amqpConfig->getChannel();
        // @codingStandardsIgnoreStart
        $channel->basic_consume($this->_queueName, '', false, false, false, false, $callbackConverter);
        // @codingStandardsIgnoreEnd
        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    public function dequeue()
    {
        $envelope = null;
        $channel = $this->_amqpConfig->getChannel();
        // @codingStandardsIgnoreStart
        /** @var AMQPMessage $message */
        try {
            $message = $channel->basic_get($this->_queueName);
        } catch (AMQPProtocolConnectionException $e) {
            throw new ConnectionLostException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        if ($message !== null) {
            $properties = array_merge(
                $message->get_properties(),
                [
                    'topic_name' => $this->getTopicName(
                        $message->delivery_info['routing_key']
                    ),
                    'delivery_tag' => $message->delivery_info['delivery_tag'],
                ]
            );
            $envelope = $this->_envelopeFactory->create(['body' => $message->body, 'properties' => $properties]);
        }

        // @codingStandardsIgnoreEnd
        return $envelope;
    }

    private function getTopicName($topicName)
    {
        if (preg_match('/^([0-9]\.)+(.*?)$/', $topicName, $matches)) {
            $topicName = $matches[2];
        }

        return $topicName;
    }
}