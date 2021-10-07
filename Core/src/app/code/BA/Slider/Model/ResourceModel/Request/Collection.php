<?php
namespace BA\Slider\Model\ResourceModel\Request;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('BA\Slider\Model\Request', 'BA\Slider\Model\ResourceModel\Request');
    }
}
