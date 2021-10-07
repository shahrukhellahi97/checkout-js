<?php
namespace BA\Punchout\Plugin;

use BA\Punchout\Helper\Session;
use Magento\Checkout\Block\Checkout\LayoutProcessor;

class CheckoutProcessor
{
    /**
     * @var \BA\Punchout\Helper\Session
     */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * This is truly horrific, but there's no other way to manage this in a dynamic way.
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $processor
     * @param callable $proceed
     * @param array $jsLayout
     * @return array
     */
    public function aroundProcess(LayoutProcessor $processor, callable $proceed, $jsLayout)
    {
        // phpcs:disable
        if ($this->session->isPunchoutCustomer()) {
            unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['customer-email']);

            foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['renders']['children'] as $key => $data) {
                if ($key != 'punchout') {
                    unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['renders']['children'][$key]);
                }
            }

            foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children'] as $key => $data) {
                unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children'][$key]);
            }
        } else {
            unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['renders']['children']['punchout']);
        }
        // phpcs:enable

        return $proceed($jsLayout);
    }
}
