<?php
namespace BA\BasysGiftCertificate\Controller\CustAccount;
 
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;

class Index implements \Magento\Framework\App\ActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirect;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirect
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        RedirectFactory $resultRedirect,
        PageFactory $resultPageFactory,
        Session $customerSession
    ) {
        $this->resultRedirect = $resultRedirect;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
    }
    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirect->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        } else {
            return $this->resultPageFactory->create();
        }
    }
}
