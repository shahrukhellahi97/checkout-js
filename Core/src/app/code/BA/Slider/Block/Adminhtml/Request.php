<?php
namespace BA\Slider\Block\Adminhtml;

class Request extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_request';/*block grid.php directory*/
        $this->_blockGroup = 'BA_Slider';
        $this->_headerText = __('Request');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
    }
}
