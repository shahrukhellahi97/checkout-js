<?php
namespace BA\BasysGiftCertificate\Model\Service;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysGiftCertificate\Model\Data\GiftCertAccountFactory;
use BA\BasysGiftCertificate\Api\GiftQuoteManagementInterface;
use BA\BasysGiftCertificate\Model\Request\Builder\GiftRequest;
use Psr\Log\LoggerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
//use Magento\Quote\Api\Data\CartInterface;
use BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface;

class GiftQuoteManagement implements GiftQuoteManagementInterface
{
    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;
    /**
     * @var \BA\BasysGiftCertificate\Model\Request\Builder\GiftRequest
     */
    protected $giftRequestBuilder;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface
     */
    protected $giftCertAccountFactory;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    public function __construct(
        CommandPoolInterface $commandPool,
        GiftRequest $giftRequestBuilder,
        GiftCertAccountFactory $giftCertAccountFactory,
        CartRepositoryInterface $quoteRepository,
        LoggerInterface $logger
    ) {
        $this->giftRequestBuilder = $giftRequestBuilder;
        $this->commandPool = $commandPool;
        $this->giftCertAccountFactory = $giftCertAccountFactory;
        $this->logger = $logger;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Apply gift card
     * @param string $certificateRef
     * @param int $quoteId
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function applyGiftCard($certificateRef, $quoteId, $giftAmt)
    {
        $quote = $this->quoteRepository->getActive($quoteId);
        //$quote = $this->quoteRepository->get($quoteId);
        $quoteExtension = $quote->getExtensionAttributes();
        $quoteExtension->setGiftEnabled(1);
        $quoteExtension->setGiftAmt($giftAmt);
        $quoteExtension->setCertificateref($certificateRef);
        $quote->setExtensionAttributes($quoteExtension);
        $this->quoteRepository->save($quote);
    }

    /**
     * Remove gift card
     * @param int $quoteId
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function removeGiftCard($quoteId)
    {
        $quote = $this->quoteRepository->getActive($quoteId);
        $quoteExtension = $quote->getExtensionAttributes();
        $quoteExtension->setGiftEnabled(0);
        $quoteExtension->setGiftAmt(0);
        $quoteExtension->setUsedGiftAmt(0);
        $quoteExtension->setCertificateref('null');
        $quote->setExtensionAttributes($quoteExtension);
        $this->quoteRepository->save($quote);
    }

    /**
     * Check gift card exist
     * @param mixed $quoteId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function checkGiftCardAdded($quoteId) : int
    {
        $quote = $this->quoteRepository->getActive($quoteId);
        $quoteExtension = $quote->getExtensionAttributes();
        $giftCardAdded = ($quoteExtension->getGiftEnabled() == 1) ? 1 : 0;
        return $giftCardAdded;
    }

    public function get($quote) : GiftCertAccountInterface
    {
        $giftAccountData = $this->giftCertAccountFactory->create();
        $quoteExtension = $quote->getExtensionAttributes();
        $giftAccountData->setGiftAmt($quoteExtension->getGiftAmt());
        $giftAccountData->setGiftEnabled($quoteExtension->getGiftEnabled());
        $giftAccountData->setGiftRefNumber($quoteExtension->getCertificateref());
        return $giftAccountData;
    }
}
