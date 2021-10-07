<?php
namespace BA\Slider\Block\Adminhtml\Request\Grid\Renderer;

use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Backend\Block\Context;

class Displayname extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $websiteManager;
    public function __construct(
        Context $context,
        WebsiteRepositoryInterface $websiteManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->websiteManager = $websiteManager;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        if (isset($row['website_id'])) {
            $website = $this->websiteManager->getById($row['website_id']);
            return $website->getName();
        }
    }
}
