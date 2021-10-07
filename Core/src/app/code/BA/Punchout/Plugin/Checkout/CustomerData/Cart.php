<?php
namespace BA\Punchout\Plugin\Checkout\CustomerData;

use BA\Punchout\Plugin\Checkout\CheckoutPlugin;

class Cart extends CheckoutPlugin
{
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, array $result)
    {

        $result['button_text'] = 'Proceed to Checkout';
        $result['is_punchout_customer'] = false;

        if ($this->sessionHelper->isPunchoutCustomer()) {
            $result['is_punchout_customer'] = true;
            $result['button_text'] = 'Transfer Cart';
        }

        return $result;
    }
}