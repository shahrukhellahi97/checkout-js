<?php
namespace BA\Punchout\Plugin\Checkout;

abstract class CheckoutPlugin
{
    /**
     * @var \BA\Punchout\Helper\Session
     */
    protected $sessionHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \BA\Punchout\Helper\Session $sessionHelper 
     * @return void 
     */
    public function __construct(
        \BA\Punchout\Helper\Session $sessionHelper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->sessionHelper = $sessionHelper;
        $this->logger = $logger;
    }
}