<?php
namespace BA\BasysCatalog\Model;

use BA\BasysCatalog\Api\Data\SourceCodeInterface;
use BA\BasysCatalog\Model\ResourceModel\SourceCode as ResourceModelSourceCode;
use Magento\Framework\Model\AbstractModel;

class SourceCode extends AbstractModel implements SourceCodeInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelSourceCode::class);
    }

    public function getId()
    {
        return $this->getData(SourceCodeInterface::ENTITY_ID);
    }

    public function setId($id)
    {
        return $this->setData(SourceCodeInterface::ENTITY_ID, $id);
    }

    public function getName()
    {
        return $this->getData(SourceCodeInterface::NAME);
    }

    public function setName($name)
    {
        return $this->setData(SourceCodeInterface::NAME, $name);
    }

    public function getCatalogId()
    {
        return $this->getData(SourceCodeInterface::CATALOG_ID);
    }

    public function setCatalogId($catalogId)
    {
        return $this->setData(SourceCodeInterface::CATALOG_ID, $catalogId);
    }

}