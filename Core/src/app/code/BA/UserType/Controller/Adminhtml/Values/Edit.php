<?php
namespace BA\UserType\Controller\Adminhtml\Values;

use BA\UserType\Controller\Adminhtml\Values;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Values
{
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__("Value List"));

        return $resultPage;
    }
}