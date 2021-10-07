<?php
namespace BA\BasysOrders\Rewrite\Magento\Checkout\Block\Onepage;

class Success extends \Magento\Checkout\Block\Onepage\Success
{
    public function getBasysOrderId()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        return $order->getData('basys_order_id');
    }
}
