<?php
namespace BA\UserType\Model\Config\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Catalog extends AbstractDb
{

    protected function _construct()
    {
        $this->_init(
            'ba_usertype_config_catalog',
            'config_catalog_id'
        );
    }

}