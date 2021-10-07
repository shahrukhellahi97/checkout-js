<?php
namespace BA\UserType\Model;

use BA\UserType\Api\Data\ConfigInterface;
use BA\UserType\Model\ResourceModel\Config as ResourceModelConfig;
use Magento\Framework\Model\AbstractModel;

class Config extends AbstractModel implements ConfigInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelConfig::class);
    }

    public function getConfigId()
    {
        return $this->getData(ConfigInterface::CONFIG_ID);
    }

    public function setConfigId($id)
    {
        return $this->setData(ConfigInterface::CONFIG_ID, $id);
    }

    public function getName()
    {
        return $this->getData(ConfigInterface::NAME);
    }

    public function setName($name)
    {
        return $this->setData(ConfigInterface::NAME, $name);
    }

    public function getCustomerGroupId()
    {
        return $this->getData(ConfigInterface::CUSTOMER_GROUP_ID);
    }

    public function setCustomerGroupId($id)
    {
        return $this->setData(ConfigInterface::CUSTOMER_GROUP_ID, $id);
    }

    public function getDescription()
    {
        return $this->getData(ConfigInterface::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(ConfigInterface::DESCRIPTION, $description);
    }

    public function getFromDate()
    {
        return $this->getData(ConfigInterface::FROM_DATE);
    }

    public function setFromDate($date)
    {
        return $this->setData(ConfigInterface::FROM_DATE, $date);
    }

    public function getToDate()
    {
        return $this->getData(ConfigInterface::TO_DATE);
    }

    public function setToDate($date)
    {
        return $this->setData(ConfigInterface::TO_DATE, $date);
    }

    public function getIsActive()
    {
        return $this->getData(ConfigInterface::IS_ACTIVE);
    }

    public function setIsActive($active)
    {
        return $this->setData(ConfigInterface::IS_ACTIVE, $active);
    }

    public function getSortOrder()
    {
        return $this->getData(ConfigInterface::SORT_ORDER);
    }

    public function setSortOrder($sortOrder)
    {
        return $this->setData(ConfigInterface::SORT_ORDER, $sortOrder);
    }

    public function getWebsiteId()
    {
        return $this->getData(ConfigInterface::WEBSITE_ID);
    }

    public function setWebsiteId($websiteId)
    {
        return $this->setData(ConfigInterface::WEBSITE_ID, $websiteId);
    }

    public function getStopProcessing()
    {
        return $this->getData(ConfigInterface::STOP_PROCESSING);
    }

    public function setStopProcessing($stop)
    {
        return $this->setData(ConfigInterface::STOP_PROCESSING, $stop);
    }
}
