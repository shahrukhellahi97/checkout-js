<?php
namespace BA\Slider\ViewModel;

use BA\Slider\Model\ResourceModel\Request\CollectionFactory as RequestFactory;

class MeetTeam extends \Magento\Framework\DataObject implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    protected $requestModel;

    /**
     * MeetTeam constructor.
     *
     */
    public function __construct(RequestFactory $requestModel)
    {
        $this->requestModel = $requestModel;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getTeam()
    {
        $meetTeamDatas =  $this->requestModel->create();
        return $meetTeamDatas;
    }
}
