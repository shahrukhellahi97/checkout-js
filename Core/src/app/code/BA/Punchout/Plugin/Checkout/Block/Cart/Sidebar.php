<?php
namespace BA\Punchout\Plugin\Checkout\Block\Cart;

use BA\Punchout\Plugin\Checkout\CheckoutPlugin;

class Sidebar extends CheckoutPlugin
{

    public function afterGetConfig(\Magento\Checkout\Block\Cart\Sidebar $subject, array $result)
    {
        if ($this->sessionHelper->isPunchoutCustomer()) {
            $result['checkoutUrl'] = '/punchout/checkout';
        }

        return $result;
    }
}