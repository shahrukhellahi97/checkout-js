<?php
namespace BA\UserType\Controller\Adminhtml\Config;

use BA\UserType\Controller\Adminhtml\Config;
use Magento\Framework\Controller\ResultFactory;

class Index extends Config
{
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__("User Type Configurations"));

        return $resultPage;
    }
}