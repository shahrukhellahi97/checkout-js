<?php
namespace BA\MeetTeam\Model\ResourceModel\Request;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('BA\MeetTeam\Model\Request', 'BA\MeetTeam\Model\ResourceModel\Request');
    }
}
