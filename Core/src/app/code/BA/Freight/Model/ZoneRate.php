<?php
namespace BA\Freight\Model;

use BA\Freight\Api\Data\ZoneRateInterface;
use BA\Freight\Model\ResourceModel\ZoneRate as ResourceModelZoneRate;
use Magento\Framework\Model\AbstractModel;

class ZoneRate extends AbstractModel implements ZoneRateInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelZoneRate::class);
    }

    public function getTableId()
    {
        return $this->getData(ZoneRateInterface::TABLE_ID);
    }

    public function setTableId($tableId)
    {
        return $this->setData(ZoneRateInterface::TABLE_ID, $tableId);
    }

    public function getCodeId()
    {
        return $this->getData(ZoneRateInterface::CODE_ID);
    }

    public function setCodeId($codeId)
    {
        return $this->setData(ZoneRateInterface::CODE_ID, $codeId);
    }

    public function getWeight()
    {
        return $this->getData(ZoneRateInterface::WEIGHT);
    }

    public function setWeight($weight)
    {
        return $this->setData(ZoneRateInterface::WEIGHT, $weight);
    }

    public function getValue()
    {
        return $this->getData(ZoneRateInterface::VALUE);
    }

    public function setValue($value)
    {
        return $this->setData(ZoneRateInterface::VALUE, $value);
    }
}
