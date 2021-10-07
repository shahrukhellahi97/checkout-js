<?php
namespace BA\BasysOrders\Api\Data;

interface PickListItemInterface
{
    /**
     * Get item value
     *
     * @return string
     */
    public function getValue();

    /**
     * Is this item default?
     *
     * @return bool
     */
    public function getIsDefault();
}
