<?php
namespace BA\BasysCatalog\Webservices\Response;

use BA\Basys\Webservices\Response\HandlerInterface;
use BA\BasysCatalog\Model\LevelFactory;

class GetLevelHandler implements HandlerInterface
{
    /**
     * @var \BA\BasysCatalog\Model\LevelFactory
     */
    protected $levelFactory;

    public function __construct(LevelFactory $levelFactory)
    {
        $this->levelFactory = $levelFactory;
    }

    public function handle($response, array $additional = [])
    {
        /** @var \BA\BasysCatalog\Model\Level $level */
        $level = $this->levelFactory->create();
    
        $level->setData($additional)
            ->setLevel($response['LevelEnquiryResult']['StockLevel'])
            ->setDue($response['LevelEnquiryResult']['StockDueDate']);

        return $level;
    }
}