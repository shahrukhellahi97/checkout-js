<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\LevelInterface;
use BA\BasysCatalog\Model\ResourceModel\Level as ResourceModelLevel;
use Magento\Framework\Model\AbstractModel;

class Level extends AbstractModel implements LevelInterface
{
    public function _construct()
    {
        $this->_init(ResourceModelLevel::class);
    }

    public function getBasysId()
    {
        return $this->getData(LevelInterface::BASYS_ID);
    }

    public function setBasysId($basysId)
    {
        return $this->setData(LevelInterface::BASYS_ID, $basysId);
    }

    public function getLevel()
    {
        return $this->getData(LevelInterface::LEVEL);
    }

    public function setLevel($level)
    {
        return $this->setData(LevelInterface::LEVEL, $level);
    }

    public function getDue()
    {
        return $this->getData(LevelInterface::DUE);
    }

    public function setDue($dueDate)
    {
        return $this->setData(LevelInterface::DUE, $dueDate);
    }
}