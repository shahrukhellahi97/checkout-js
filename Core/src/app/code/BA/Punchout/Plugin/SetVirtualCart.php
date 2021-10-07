<?php
namespace BA\Punchout\Plugin;

use BA\Punchout\Helper\Session;

class SetVirtualCart
{
    /**
     * @var \BA\Punchout\Helper\Session
     */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    // public function aroundGetIsVirtual(\Magento\Quote\Model\Quote $subject, callable $proceed)
    // {
    //     if ($this->session->isPunchoutCustomer()) {
    //         return true;
    //     }

    //     return $proceed();
    // }

    public function aroundIsVirtual(\Magento\Quote\Model\Quote $subject, callable $proceed)
    {
        if ($this->session->isPunchoutCustomer()) {
            return true;
        }

        return $proceed();
    }
}
