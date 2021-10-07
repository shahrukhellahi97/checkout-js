<?php
namespace BA\BasysGiftCertificate\Model\Service;

use BA\Basys\Webservices\Command\CommandPoolInterface;
// use BA\BasysGiftCertificate\Model\GiftCertAccountFactory;
use BA\BasysGiftCertificate\Api\GiftManagementInterface;
use BA\BasysGiftCertificate\Model\Request\Builder\GiftRequest;
use Psr\Log\LoggerInterface;
// use Magento\Quote\Api\CartRepositoryInterface;
// use Magento\Quote\Api\Data\CartInterface;
// use BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface;

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
    /**
     * @var \BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface
     */
    protected $giftCertAccountFactory;
    protected $quoteRepository;

    public function __construct(
        CommandPoolInterface $commandPool,
        GiftRequest $giftRequestBuilder,
        LoggerInterface $logger
    ) {
        $this->giftRequestBuilder = $giftRequestBuilder;
        $this->commandPool = $commandPool;
        $this->logger = $logger;
    }

    private function getBalance($certificateReference)
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

    public function checkBalance($certificateReference)
    {
        if (!preg_match("/^([0-9A-Z]{4}-){3}[0-9A-Z]{4}$/i", trim($certificateReference))) {
            $balanceInfo['Error'] = 'Invalid gift certificate';
            return $balanceInfo;
        }
        $balanceInfo['Balance'] = 50;
        
       // $balanceInfo = $this->getBalance($certificateReference);
        return $balanceInfo;
    }
}
