<?php
namespace BA\Basys\Webservices\Command;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\ObjectManager\TMapFactory;

class CommandPool implements CommandPoolInterface
{
    /**
     * @var \BA\Basys\Webservices\Command\CommandInterface[] | TMap
     */
    private $commands;

    public function __construct(
        TMapFactory $tmapFactory,
        array $commands = []
    ) {
        $this->commands = $tmapFactory->create(
            [
                'array' => $commands,
                'type' => CommandInterface::class
            ]
        );
    }

    public function get($commandCode): CommandInterface
    {
        if (!isset($this->commands[$commandCode])) {
            throw new NotFoundException(
                __('The "%1" command doesn\'t exist. Verify the command and try again.', $commandCode)
            );
        }

        return $this->commands[$commandCode];
    }
}
