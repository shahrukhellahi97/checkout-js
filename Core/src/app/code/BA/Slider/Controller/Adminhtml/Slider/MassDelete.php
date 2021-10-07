<?php
namespace BA\Slider\Controller\Adminhtml\Slider;

use BA\Slider\Model\RequestFactory;
use Magento\Backend\App\Action\Context;
use BA\Slider\Model\ResourceModel\Request as SliderResourceModel;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $requestModel;
    protected $sliderResourceModel;
    
    public function __construct(
        Context $context,
        RequestFactory $requestModel,
        SliderResourceModel $sliderResourceModel
    ) {
        parent::__construct($context);
        $this->requestModel = $requestModel;
        $this->sliderResourceModel = $sliderResourceModel;
    }
   
    public function execute()
    {
        $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addErrorMessage(__('Please select sliders(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = $this->requestModel->create();
                    $sliderModel = $this->sliderResourceModel->load($model, $id);
                    $sliderModel->delete($model);
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
