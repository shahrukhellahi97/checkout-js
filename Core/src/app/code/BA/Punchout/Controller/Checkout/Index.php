<?php
namespace BA\Punchout\Controller\Checkout;

use BA\Punchout\Api\Processor\OrderMessageProcesserInterface;
use BA\Punchout\Api\RequestRepositoryInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \BA\Punchout\Helper\Session
     */
    protected $session;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \BA\Punchout\Api\RequestRepositoryInterface
     */
    protected $requestRepository;

    /**
     * @var \BA\Punchout\Api\Processor\OrderMessageProcesserInterface
     */
    protected $orderMessageProcesser;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \BA\Punchout\Helper\Session $session,
        \Psr\Log\LoggerInterface $logger,
        OrderMessageProcesserInterface $orderMessageProcesser,
        RequestRepositoryInterface $requestRepository 
    ) {
        $this->session = $session;
        $this->logger  = $logger;
        $this->pageFactory = $pageFactory;
        $this->requestRepository = $requestRepository;
        $this->orderMessageProcesser = $orderMessageProcesser;

        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->pageFactory->create();
        $result->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        $result->addHandle('punchout_checkout_index');

        return $result;
    }
}