<?php
namespace BA\Slider\Block\Adminhtml\Request\Grid\Renderer;

use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Backend\Block\Context;

class Enabled extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        if (isset($row['enabled']) && ($row['enabled'] == '1')) {
            return 'Yes';
        } else {
            return 'No';
        }
    }
}
