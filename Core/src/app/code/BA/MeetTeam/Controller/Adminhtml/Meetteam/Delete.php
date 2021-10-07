<?php
namespace BA\MeetTeam\Controller\Adminhtml\Meetteam;

use Magento\Backend\App\Action\Context;
use BA\MeetTeam\Model\RequestFactory;
use BA\MeetTeam\Model\ResourceModel\Request as MeetTeamResourceModel;

class Delete extends \Magento\Backend\App\Action
{
    protected $requestModel;
    protected $sessionData;
    protected $meetTeamResourceModel;

    public function __construct(
        Context $context,
        RequestFactory $requestModel,
        MeetTeamResourceModel $meetTeamResourceModel
    ) {
        $this->requestModel = $requestModel;
        $this->meetTeamResourceModel = $meetTeamResourceModel;
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
            $teamModel = $this->meetTeamResourceModel->load($model, $id);
            $teamModel->delete($model);
            $this->messageManager->addSuccessMessage(__('Delete successfully !'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
