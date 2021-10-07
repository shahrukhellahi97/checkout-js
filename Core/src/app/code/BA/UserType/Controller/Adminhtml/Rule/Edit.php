<?php
namespace BA\UserType\Controller\Adminhtml\Rule;

use BA\UserType\Controller\Adminhtml\Rule;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Rule
{
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__("Rule Management"));

        return $resultPage;
    }
}