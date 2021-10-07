<?php
namespace BA\BasysCatalog\Observer\Frontend\Controller;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use BA\BasysCatalog\Helper\Pricing;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\StoreManagerInterface;
use BA\BasysCatalog\Observer\AbstractPricingObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class ActionPredispatch extends AbstractPricingObserver implements ObserverInterface
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        Pricing $pricing,
        CheckoutSession $checkoutSession,
        CartRepositoryInterface $cartRepository,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($pricing);
        
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $observer->getEvent()->getRequest();
        $currency = $request->getParam('currency');

        if ($currency != null) {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore();
            $store->setCurrentCurrencyCode($currency);
        }

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->checkoutSession->getQuote();
        $items = [];
        
        if ($items = $quote->getItems() && count($items) >= 1) {
            $this->setCustomPrice($quote);
            
            $this->cartRepository->save($quote);
        }
    }
}
