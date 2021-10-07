<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Adflex\Payments\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Info
 *
 * @package Adflex\Payments\Block\Adminhtml\Order
 */
class Info extends Template
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    private $_order;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $_json;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Json $json,
        array $data = []
    ) {
        $this->_order = $registry->registry('current_order');
        $this->_json = $json;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Sales\Model\Order\Payment
     * Returns payment object for use.
     */
    public function getPayment()
    {
        return $this->_order->getPayment();
    }

    /**
     * @return \Magento\Sales\Model\Order|mixed|null
     * Returns order object for use.
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @param null $payment
     * @return array|bool|float|int|mixed|string|null
     * Gets additional data from serialized array.
     */
    public function getAdditionalData($payment = null)
    {
        if (is_null($payment)) {
            $payment = $this->getPayment();
        }
        return $this->_json->unserialize($payment->getData('additional_data'));
    }

}
