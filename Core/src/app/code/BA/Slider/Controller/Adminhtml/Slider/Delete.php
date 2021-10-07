<?php
namespace BA\Slider\Controller\Adminhtml\Slider;

use BA\Slider\Model\RequestFactory;
use Magento\Backend\App\Action\Context;
use BA\Slider\Model\ResourceModel\Request as SliderResourceModel;

class Delete extends \Magento\Backend\App\Action
{
    protected $requestModel;
    protected $sliderResourceModel;

    public function __construct(
        Context $context,
        RequestFactory $requestModel,
        SliderResourceModel $sliderResourceModel
    ) {
        $this->requestModel = $requestModel;
        $this->sliderResourceModel = $sliderResourceModel;
        parent::__construct($context);
    }
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->requestModel->create();
        try {
                $sliderModel = $this->sliderResourceModel->load($model, $id);
                $sliderModel->delete($model);
                $this->messageManager->addSuccessMessage(__('Delete successfully !'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
