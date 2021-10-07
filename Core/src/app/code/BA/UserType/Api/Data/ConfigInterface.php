<?php
namespace BA\UserType\Api\Data;

interface ConfigInterface
{
    const SCHEMA = 'ba_usertype_config';

    const CONFIG_ID = 'config_id';
    const NAME = 'name';
    const CUSTOMER_GROUP_ID = 'customer_group_id';
    const DESCRIPTION = 'description';
    const FROM_DATE = 'from_date';
    const TO_DATE = 'to_date';
    const IS_ACTIVE = 'is_active';
    const SORT_ORDER = 'sort_order';
    const WEBSITE_ID = 'website_id';
    const STOP_PROCESSING = 'stop_processing';

    public function getConfigId();

    public function setConfigId($id);

    public function getName();

    public function setName($name);

    public function getCustomerGroupId();

    public function setCustomerGroupId($id);

    public function getDescription();

    public function setDescription($description);

    public function getFromDate();

    public function setFromDate($date);

    public function getToDate();

    public function setToDate($date);

    public function getIsActive();

    public function setIsActive($active);

    public function getSortOrder();

    public function setSortOrder($sortOrder);

    public function getWebsiteId();

    public function setWebsiteId($websiteId);

    public function getStopProcessing();

    public function setStopProcessing($stop);
}