<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\DivisionInterface;
use BA\BasysCatalog\Model\ResourceModel\Division as ResourceModelDivision;
use Magento\Framework\Model\AbstractModel;

class Division extends AbstractModel implements DivisionInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelDivision::class);
    }

    public function getId()
    {
        return $this->getData(DivisionInterface::ENTITY_ID);
    }

    public function setId($divisionId)
    {
        return $this->setData(DivisionInterface::ENTITY_ID, $divisionId);
    }

    public function getName()
    {
        return $this->getData(DivisionInterface::NAME);
    }

    public function setName($name)
    {
        return $this->getData(DivisionInterface::NAME, $name);
    }

}