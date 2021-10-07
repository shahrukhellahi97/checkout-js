<?php
namespace BA\BasysCatalog\Webservices\Response\Async;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\BasysCatalog\Model\LevelFactory as LevelFactory;
use BA\BasysCatalog\Model\ResourceModel\LevelFactory as LevelResourceFactory;
use Psr\Log\LoggerInterface;

class GetLevelHandler implements HandlerInterface
{
    /**
     * @var \BA\BasysCatalog\Model\LevelFactory
     */
    protected $levelFactory;

    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\LevelFactory
     */
    protected $levelResourceFactory;

    /**
     * @var \Laminas\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        LevelResourceFactory $levelResourceFactory,
        LevelFactory $levelFactory,
        LoggerInterface $logger
    ) {
        $this->levelFactory = $levelFactory;
        $this->levelResourceFactory = $levelResourceFactory;
        $this->logger = $logger;
    }

    public function handle($response, array $additional = [])
    {
        /** @var \BA\BasysCatalog\Model\ResourceModel\Level $resource */
        $resource = $this->levelResourceFactory->create();

        try {
            $resource->save($response);
        } catch (\Exception $e) {
            $this->logger->err('Unable to save product levels', ['msg' => $e->getMessage()]);
        }
    }
}
