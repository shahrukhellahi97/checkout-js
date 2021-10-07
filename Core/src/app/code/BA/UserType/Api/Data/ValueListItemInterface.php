<?php
namespace BA\UserType\Api\Data;

interface ValueListItemInterface
{
    const SCHEMA = 'ba_usertype_value_list_item';

    const ITEM_ID = 'item_id';
    const VALUE_LIST_ID = 'list_id';
    const VALUE = 'value';

    /**
     * @return int
     */
    public function getItemId();

    /**
     * @param int $itemId
     * @return self
     */
    public function setItemId($itemId);

    /**
     * @return int
     */
    public function getListId();

    /**
     * @param int $listId
     * @return self
     */
    public function setListId($listId);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return self
     */
    public function setValue($value);
}
