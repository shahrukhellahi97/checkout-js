<?php
namespace BA\MeetTeam\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use BA\MeetTeam\Model\ResourceModel\Request\CollectionFactory as RequestFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class LoadTeamMembers extends Template implements BlockInterface
{

    protected $_template = "widget/teammembers.phtml";
    protected $requestFactory;
    protected $storeManager;

    public function __construct(
        Context $context,
        RequestFactory $requestFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->requestFactory = $requestFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }
    public function getMemberCollection()
    {
        $teamIds = $this->getData('list_team_members');
        $membersModel = $this->requestFactory->create()->addFieldToFilter('id', ['in'=>$teamIds]);
        return $membersModel;
    }
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}
