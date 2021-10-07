<?php
namespace BA\BasysGiftCertificate\Block\Sales\Order;

class Gift extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Order
     */
    protected $_order;
    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }
    /**
     * Initialize all order totals relates with tax
     *
     * @return \Magento\Tax\Block\Sales\Order\Tax
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $giftAmt = new \Magento\Framework\DataObject(
            [
                'code' => 'gift_amt',
                'strong' => false,
                'value' => $this->getOrder()->getData('gift_amt'),
                'label' => __('Gift Amt'),
            ]
        );

        $parent->addTotal($giftAmt, 'gift_amt');
        return $this;
    }
}
