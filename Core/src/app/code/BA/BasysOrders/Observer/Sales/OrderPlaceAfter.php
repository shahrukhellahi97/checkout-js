<?php
namespace BA\BasysOrders\Observer\Sales;

use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use BA\BasysOrders\Api\OrderManagementInterface;

class OrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \BA\BasysOrders\Api\OrderManagementInterface
     */
    protected $orderManagement;

    public function __construct(
        OrderManagementInterface $orderManagement,
        CheckoutSession $checkoutSession,
        LoggerInterface $logger
    ) {
        $this->orderManagement = $orderManagement;
        $this->checkoutSession = $checkoutSession;
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
            $order = $observer->getEvent()->getOrder();
            // todo: refactor to use gateway
            $this->orderManagement->create($order);
            $this->checkoutSession->unsEnableGiftCard();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
