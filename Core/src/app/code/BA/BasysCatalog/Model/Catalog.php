<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\ResourceModel\Catalog as ResourceModelCatalog;
use Magento\Framework\Model\AbstractModel;

class Catalog extends AbstractModel implements CatalogInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelCatalog::class);
    }

    public function getId()
    {
        return $this->getData(CatalogInterface::ENTITY_ID);
    }

    public function setId($id)
    {
        return $this->setData(CatalogInterface::ENTITY_ID, $id);
    }

    public function getName()
    {
        return $this->getData(CatalogInterface::NAME);
    }

    public function setName($name)
    {
        return $this->setData(CatalogInterface::NAME, $name);
    }

    public function getCurrency()
    {
        return $this->getData(CatalogInterface::CURRENCY);
    }

    public function setCurrency($currency)
    {
        return $this->setData(CatalogInterface::CURRENCY, $currency);
    }

    public function getDivisionId()
    {
        return $this->getData(CatalogInterface::DIVISION_ID);
    }

    public function setDivisionId($divisionId)
    {
        return $this->setData(CatalogInterface::DIVISION_ID, $divisionId);
    }

}