<?php
namespace BA\MeetTeam\Block\Adminhtml\Request\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('checkmodule_request_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Edit Information'));
    }
}
