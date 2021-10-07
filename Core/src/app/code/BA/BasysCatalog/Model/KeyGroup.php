<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\KeyGroupInterface;
use BA\BasysCatalog\Model\ResourceModel\KeyGroup as ResourceModelKeyGroup;
use Magento\Framework\Model\AbstractModel;

class KeyGroup extends AbstractModel implements KeyGroupInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelKeyGroup::class);
    }
    
    public function getId()
    {
        return $this->getData(KeyGroupInterface::ENTITY_ID);
    }

    public function setId($id)
    {
        return $this->setData(KeyGroupInterface::ENTITY_ID, $id);
    }

    public function getName()
    {
        return $this->getData(KeyGroupInterface::NAME);
    }

    public function setName($name)
    {
        return $this->setData(KeyGroupInterface::NAME, $name);
    }

    public function getDivisionId()
    {
        return $this->getData(KeyGroupInterface::DIVISION_ID);
    }

    public function setDivisionId($divisionId)
    {
        return $this->setData(KeyGroupInterface::DIVISION_ID, $divisionId);
    }
}