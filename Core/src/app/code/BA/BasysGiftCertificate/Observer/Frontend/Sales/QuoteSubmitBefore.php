<?php
namespace BA\BasysGiftCertificate\Observer\Frontend\Sales;

use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use BA\BasysGiftCertificate\Api\Data\GiftCertAccountInterface;

class QuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    protected $checkoutSession;
    protected $giftAccountData;

    public function __construct(
        CheckoutSession $checkoutSession,
        GiftCertAccountInterface $giftAccountData,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->giftAccountData = $giftAccountData ;
        $this->logger = $logger;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        try {
           
            // $quote = $observer->getQuote();
            // $order = $observer->getOrder();
            // $order->setData('gift_cards_amount', 0.00);
            // $order->setData('base_gift_cards_amount', 0.00);
            // $order->setData('gift_amt', -$this->giftAccountData->getGiftAmtUsed());
            // $order->setData(
            //     'certificateref', 
            //     $this->giftAccountData->getGiftRefNumber()
            // );

            
        } catch (\Exception $e) {
            $this->logger->info('observer error gift certificate');
            $this->logger->error($e->getMessage());
        }
    }
}
