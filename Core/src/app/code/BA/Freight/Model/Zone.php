<?php
namespace BA\Freight\Model;

use BA\Freight\Api\Data\ZoneInterface;
use BA\Freight\Model\ResourceModel\Zone as ResourceModelZone;
use Magento\Framework\Model\AbstractModel;

class Zone extends AbstractModel implements ZoneInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelZone::class);
    }

    public function getTableId()
    {
        return $this->getData(ZoneInterface::TABLE_ID);
    }

    public function setTableId($id)
    {
        return $this->setData(ZoneInterface::TABLE_ID, $id);
    }

    public function getCountryId()
    {
        return $this->getData(ZoneInterface::COUNTRY_ID);
    }

    public function setCountryId($countryCode)
    {
        return $this->setData(ZoneInterface::COUNTRY_ID, $countryCode);
    }

    public function getCodeId()
    {
        return $this->getData(ZoneInterface::CODE_ID);
    }

    public function setCodeId($id)
    {
        return $this->setData(ZoneInterface::CODE_ID, $id);
    }
}
