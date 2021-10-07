<?php
namespace BA\Basys\Webservices\Command\DelayedDelivery;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Wire\AMQPTable;

class Infrastructure
{
    const MAX_BITS = 28;

    const MAX_LEVEL = self::MAX_BITS - 1;

    const DLX = 'delayed.delivery';

    public function build(AMQPChannel $channel)
    {   
        $channel->exchange_declare(static::DLX, 'topic', false);
        $bindingKey = '1.#';

        for ($level = static::MAX_LEVEL; $level >= 0; $level--) {
            $currentLevel = $this->getLevel($level);
            $nextLevel = $this->getLevel($level - 1);

            $channel->exchange_declare(
                $currentLevel,
                'topic',
                false,
            );

            $arguments = [
                'x-queue-mode' => 'lazy',
                'x-message-ttl' => pow(2, $level) * 1000,
                'x-dead-letter-exchange' => $level > 0 ? $nextLevel : static::DLX,
            ];

            $channel->queue_declare(
                $currentLevel,
                false,
                false,
                false,
                true,
                false,
                new AMQPTable($arguments)
            );

            $channel->queue_bind($currentLevel, $currentLevel, $bindingKey);
            $bindingKey = "*." . $bindingKey;
        }

        $bindingKey = "0.#";

        for ($level = static::MAX_LEVEL; $level >= 1; $level--) {
            $currentLevel = $this->getLevel($level);
            $nextLevel = $this->getLevel($level - 1);

            $channel->exchange_bind(
                $nextLevel, 
                $currentLevel,
                $bindingKey
            );

            $bindingKey = "*." . $bindingKey;
        }

        $channel->exchange_bind(static::DLX, $this->getLevel(0), $bindingKey);
    }

    public function destroy(AMQPChannel $channel)
    {
        $channel->exchange_delete(static::DLX);

        for ($level = static::MAX_LEVEL; $level >= 0; $level--) {
            $channel->queue_delete($this->getLevel($level));
            $channel->exchange_delete($this->getLevel($level));
        }
    }

    public function getRoutingKey(int $delay, string $address, &$startingLevel)
    {
        $delay = max(0, $delay);
        $bits  = str_pad(decbin($delay), static::MAX_BITS, '0');

        $result = [];

        for ($level = static::MAX_LEVEL; $level >= 0; $level--) {
            if ($startingLevel == 0 && $bits[$level] != 0) {
                $startingLevel = $level;
            } 

            $result[] = $bits[$level];
        }

        $result[] = $address;

        return implode('.', $result);
    }

    public function getLevel(int $level)
    {
        return 'delayed.delivery-' . $level;
    }
}
