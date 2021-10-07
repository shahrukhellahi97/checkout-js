<?php
namespace BA\BasysGiftCertificate\ViewModel;

use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class CheckBalance implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    private $checkoutSession;
      
    public function __construct(
        StoreManagerInterface $storeManager,
        CheckoutSession $checkoutSession,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
    }
    public function getAjaxUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl().
        'checkbalance/index/checkbalance';
    }
    public function getAjaxUrlApplyButton()
    {
        return $this->storeManager->getStore()->getBaseUrl().
        'applygiftcard/index/applygiftcard';
    }
    public function getItemsCount()
    {
        return count($this->checkoutSession->getQuote()->getAllVisibleItems());
    }
}
