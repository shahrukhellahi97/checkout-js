<?php
namespace BA\Slider\Controller\Adminhtml\Slider;

use BA\Slider\Model\Request;
use Magento\Backend\App\Action\Context;

class MassStatus extends \Magento\Backend\App\Action
{
    protected $requestModel;
    /**
     * @param Context $context
     * 
     */
    public function __construct(
        Context $context,
        Request $requestModel
    ) {
        parent::__construct($context);
        $this->requestModel = $requestModel;
    }
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $ids = $this->getRequest()->getParam('id');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addErrorMessage(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->requestModel->load($id);
                    $row->setData('status', $status)->save();
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
