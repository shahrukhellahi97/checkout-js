<?php
namespace BA\BasysOrders\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class AvailablePaymentMethods implements OptionSourceInterface
{
    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;

    public function __construct(
        \Magento\Payment\Helper\Data $paymentHelper
    ) {
        $this->paymentHelper = $paymentHelper;
    }

    public function toOptionArray()
    {
        $result = [];

        foreach ($this->paymentHelper->getPaymentMethods() as $paymentMethod) {

        }

        return $result;
    }
}