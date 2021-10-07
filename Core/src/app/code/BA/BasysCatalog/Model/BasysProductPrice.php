<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\BasysProductPriceInterface;
use BA\BasysCatalog\Model\ResourceModel\BasysProductPrice as ResourceModelBasysProductPrice;
use Magento\Framework\DataObject;

class BasysProductPrice extends DataObject implements BasysProductPriceInterface
{
    public function getCatalogId()
    {
        return $this->getData(BasysProductPriceInterface::CATALOG_ID);
    }

    public function setCatalogId($id)
    {
        return $this->setData(BasysProductPriceInterface::CATALOG_ID, $id);
    }

    public function getBasysId()
    {
        return $this->getData(BasysProductPriceInterface::BASYS_ID);
    }

    public function setBasysId($id)
    {
        return $this->setData(BasysProductPriceInterface::BASYS_ID, $id);
    }

    public function getType()
    {
        return $this->getData(BasysProductPriceInterface::TYPE_ID);
    }

    public function setType($typeId)
    {
        return $this->setData(BasysProductPriceInterface::TYPE_ID, $typeId);
    }

    public function getPrice()
    {
        return $this->getData(BasysProductPriceInterface::PRICE);
    }

    public function setPrice($price)
    {
        return $this->setData(BasysProductPriceInterface::PRICE, $price);
    }

    public function getBreak()
    {
        return $this->getData(BasysProductPriceInterface::BREAK);
    }

    public function setBreak($break)
    {
        return $this->setData(BasysProductPriceInterface::BREAK, $break);
    }
}
