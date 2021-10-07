<?php
namespace BA\Punchout\Block;

use Magento\Checkout\Block\Onepage\Link;

class Checkout extends Link
{
    /**
     * @var \BA\Punchout\Helper\Session
     */
    protected $sessionHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \BA\Punchout\Helper\Session $sessionHelper,
        array $data = []
    ) {
        $this->sessionHelper = $sessionHelper;
        parent::__construct($context, $checkoutSession, $checkoutHelper, $data);
    }

    public function getCheckoutUrl()
    {
        if ($this->isPunchoutCustomer()) {
            return $this->getUrl('punchout/checkout');
        } else {
            return parent::getCheckoutUrl();
        }
    }

    public function isPunchoutCustomer(): bool
    {
        return $this->sessionHelper->isPunchoutCustomer();
    }   
}