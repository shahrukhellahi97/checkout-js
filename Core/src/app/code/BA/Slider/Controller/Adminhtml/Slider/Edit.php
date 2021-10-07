<?php
namespace BA\Slider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use BA\Slider\Model\RequestFactory;
use BA\Slider\Model\ResourceModel\Request as SliderResourceModel;
use Magento\Backend\Model\Session;
use Magento\Framework\Registry;

class Edit extends \Magento\Backend\App\Action
{
    protected $requestModel;
    protected $sessionData;
    protected $sliderResourceModel;

    public function __construct(
        Context $context,
        RequestFactory $requestModel,
        Registry $registryObj,
        Session $sessionData,
        SliderResourceModel $sliderResourceModel
    ) {
        $this->requestModel = $requestModel;
        $this->registryObj = $registryObj;
        $this->sessionData = $sessionData;
        $this->sliderResourceModel = $sliderResourceModel;
        parent::__construct($context);
    }
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->requestModel->create();
        
        // 2. Initial checking
        if ($id) {
            $this->sliderResourceModel->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This row no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->sessionData->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
       
        $this->registryObj->register('request_request', $model);
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
