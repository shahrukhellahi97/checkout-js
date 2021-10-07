<?php
namespace BA\Slider\Model\ResourceModel;
/**
 * Request resource
 */
class Request extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('ba_slider', 'id');
    }
}
