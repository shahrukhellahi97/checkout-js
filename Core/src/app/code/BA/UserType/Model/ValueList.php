<?php
namespace BA\UserType\Model;

use BA\UserType\Model\ResourceModel\ValueList as ResourceModelValueList;
use BA\UserType\Api\Data\ValueListInterface;
use Magento\Framework\Model\AbstractModel;

class ValueList extends AbstractModel implements ValueListInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelValueList::class);
    }

    public function getListId()
    {
        return $this->getData(ValueListInterface::LIST_ID);
    }

    public function setListId($id)
    {
        return $this->setData(ValueListInterface::LIST_ID, $id);
    }

    public function getLabel()
    {
        return $this->getData(ValueListInterface::LABEL);
    }

    public function setLabel($label)
    {
        return $this->setData(ValueListInterface::LABEL, $label);
    }

    public function getComment()
    {
        return $this->getData(ValueListInterface::COMMENT);
    }

    public function setComment($comment)
    {
        return $this->setData(ValueListInterface::COMMENT, $comment);
    }
    
}