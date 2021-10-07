<?php
namespace BA\BasysGiftCertificate\Model;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysGiftCertificate\Api\GiftManagementInterface;
use BA\BasysGiftCertificate\Model\Request\Builder\GiftRequest;
use Psr\Log\LoggerInterface;

class GiftManagement implements GiftManagementInterface
{
    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    protected $giftRequestBuilder;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        CommandPoolInterface $commandPool,
        GiftRequest $giftRequestBuilder,
        LoggerInterface $logger
    ) {
        $this->giftRequestBuilder = $giftRequestBuilder;
        $this->commandPool = $commandPool;
        $this->logger = $logger;
    }

    public function checkBalance($certificateReference)
    {
        $request = $this->giftRequestBuilder->build($certificateReference);
        $command = $this->commandPool->get('check_balance');
       
        try {
            $balanceInfo = $command->execute($request);
            return $balanceInfo;

        } catch (\Exception $e) {
            $this->logger->info('request error');
            $this->logger->error($e->getMessage());
        }
    }
}
