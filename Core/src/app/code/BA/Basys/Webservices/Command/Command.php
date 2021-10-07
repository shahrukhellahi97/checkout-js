<?php
namespace BA\Basys\Webservices\Command;

use BA\Basys\Webservices\Http\ClientInterface;
use BA\Basys\Webservices\Http\TransferFactoryInterface;
use BA\Basys\Webservices\Request\RequestBuilderInterface;
use BA\Basys\Webservices\Response\HandlerInterface;

class Command implements CommandInterface
{
    /**
     * @var \BA\Basys\Webservices\Http\ClientInterface
     */
    protected $client;

    /**
     * @var \BA\Basys\Webservices\Http\TransferFactoryInterface
     */
    protected $transferFactory;

    /**
     * @var \BA\Basys\Webservices\Response\HandlerInterface
     */
    protected $handler;

    /**
     * @var \BA\Basys\Webservices\Request\RequestBuilderInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $commandName;

    /**
     * @var array
     */
    protected $additional;

    public function __construct(
        string $commandName,
        RequestBuilderInterface $request,
        TransferFactoryInterface $transferFactory,
        ClientInterface $client,
        HandlerInterface $handler = null
    ) {
        
        $this->request = $request;
        $this->transferFactory = $transferFactory;
        $this->client  = $client;
        $this->handler = $handler;
        $this->commandName = $commandName;
    }

    public function execute(array $arguments, $additional = [])
    {
        $transfer = $this->transferFactory->create(
            $this->request->build($arguments)
        );

        $result = $this->client->execute($transfer);

        if ($this->handler) {
            return $this->handler->handle($result, $additional);
        }

        return $result;
    }

    public function getName(): string
    {
        return $this->commandName;
    }

    public function setDurability($isDurable, $retries = 5): void
    {
        
    }
}