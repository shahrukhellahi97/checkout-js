<?php
namespace BA\MeetTeam\Controller\Adminhtml\Meetteam;

use Magento\Backend\App\Action\Context;
use BA\MeetTeam\Model\RequestFactory;
use BA\MeetTeam\Model\ResourceModel\Request as MeetTeamResourceModel;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $requestModel;
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
        $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addErrorMessage(__('Please select rows(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = $this->requestModel->create();
                    $teamModel = $this->meetTeamResourceModel->load($model, $id);
                    $teamModel->delete($model);
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
