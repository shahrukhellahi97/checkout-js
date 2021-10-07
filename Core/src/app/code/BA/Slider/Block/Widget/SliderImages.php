<?php
namespace BA\Slider\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use BA\Slider\Model\ResourceModel\Request\CollectionFactory as RequestFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class SliderImages extends Template implements BlockInterface
{

    protected $_template = "widget/owlcarousel.phtml";
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
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $sliderModel = $this->requestFactory->create()
        ->addFieldToFilter('website_id', ['in'=>$websiteId])
        ->addFieldToFilter('enabled', ['eq' => 1])
        ->setOrder('sort_order', 'ASC');
        return $sliderModel;
    }
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}
