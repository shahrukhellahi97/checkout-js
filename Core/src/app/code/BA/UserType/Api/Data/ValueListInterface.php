<?php
namespace BA\UserType\Api\Data;

interface ValueListInterface
{
    const SCHEMA = 'ba_usertype_value_list';

    const LIST_ID = 'list_id';
    const LABEL = 'label';
    const COMMENT = 'comment';

    /**
     * @return int
     */
    public function getListId();

    /**
     * @param int $id
     * @return self
     */
    public function setListId($id);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return string
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getComment();

    /**
     * @param string $comment
     * @return self
     */
    public function setComment($comment);
}