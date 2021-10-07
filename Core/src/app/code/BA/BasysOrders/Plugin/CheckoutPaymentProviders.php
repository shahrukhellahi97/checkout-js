<?php
namespace BA\BasysOrders\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Customer\Model\Session;

class CheckoutPaymentProviders
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    public function aroundProcess(LayoutProcessor $processor, callable $proceed, $jsLayout)
    {
        // phpcs:disable
        if (!$this->customerSession->isLoggedIn()) {
            // Remove all ba_* order methods
            foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['renders']['children'] as $key => $data) {
                if (preg_match('/^ba_/i', $key)) {
                    // unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['renders']['children'][$key]);
                }
            }
        }
        // phpcs:enable

        return $proceed($jsLayout);
    }
}
