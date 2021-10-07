<?php
namespace BA\UserType\Model;

use BA\UserType\Api\Data\ValueListItemInterface;
use BA\UserType\Model\ResourceModel\ValueListItem as ResourceModelValueListItem;
use Magento\Framework\Model\AbstractModel;

class ValueListItem extends AbstractModel implements ValueListItemInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelValueListItem::class);
    }


    public function getItemId()
    {
        return $this->getData(ValueListItemInterface::ITEM_ID);
    }

    public function setItemId($itemId)
    {
        return $this->setData(ValueListItemInterface::ITEM_ID, $itemId);
    }

    public function getListId()
    {
        return $this->getData(ValueListItemInterface::VALUE_LIST_ID);
    }

    public function setListId($listId)
    {
        return $this->setData(ValueListItemInterface::VALUE_LIST_ID, $listId);
    }

    public function getValue()
    {
        return $this->getData(ValueListItemInterface::VALUE);
    }

    public function setValue($value)
    {
        return $this->setData(ValueListItemInterface::VALUE, $value);
    }
}