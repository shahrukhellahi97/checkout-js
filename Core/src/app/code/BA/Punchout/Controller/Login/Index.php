<?php
namespace BA\Punchout\Controller\Login;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \BA\Punchout\Helper\Session
     */
    protected $session;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\RequestInterface $request,
        \BA\Punchout\Helper\Session $session,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->session = $session;
        $this->request = $request;
        $this->logger  = $logger;

        parent::__construct($context);
    }

    public function execute()
    {
        $requestToken = $this->request->getParam('t', 'x');

        try {
            $this->session->loginWithToken($requestToken);
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        // Redirect customer
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('/');

        return $redirect;
    }
}