<?php
namespace BA\UserType\Model\Config;

use BA\UserType\Model\Config\ResourceModel\Catalog as ResourceModelCatalog;
use Magento\Framework\Model\AbstractModel;

class Catalog extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModelCatalog::class);
    }

    public function getId()
    {
        return $this->getData('config_catalog_id');
    }

    public function setId($id)
    {
        return $this->setData('config_catalog_id', $id);
    }

    public function getConfigId()
    {
        return $this->getData('config_id');
    }

    public function setConfigId($id)
    {
        return $this->setData('config_id', $id);
    }

    public function getCatalogId()
    {
        return $this->getData('catalog_id');
    }

    public function setCatalogId($id)
    {
        return $this->setData('catalog_id', $id);
    }

    public function getIsActive()
    {
        return (bool) $this->getData('is_active');
    }

    public function setIsActive($value)
    {
        return $this->setData('is_active', (bool) $value);
    }
}