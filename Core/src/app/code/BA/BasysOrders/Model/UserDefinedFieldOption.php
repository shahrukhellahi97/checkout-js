<?php
namespace BA\BasysOrders\Model;

use BA\BasysOrders\Api\Data\UserDefinedFieldOptionInterface;
use BA\BasysOrders\Model\ResourceModel\UserDefinedFieldOption as ResourceModelUserDefinedFieldOption;
use Magento\Framework\Model\AbstractModel;

class UserDefinedFieldOption extends AbstractModel implements UserDefinedFieldOptionInterface
{
    public function _construct()
    {
        $this->_init(ResourceModelUserDefinedFieldOption::class);
    }
    
    public function getDivisionId()
    {
        return $this->getData(UserDefinedFieldOptionInterface::DIVISION_ID);
    }

    public function setDivisionId($divisionId)
    {
        return $this->setData(UserDefinedFieldOptionInterface::DIVISION_ID, $divisionId);
    }

    public function getSequenceNo()
    {
        return $this->getData(UserDefinedFieldOptionInterface::SEQUENCE_ID);
    }

    public function setSequenceNo($sequenceNo)
    {
        return $this->setData(UserDefinedFieldOptionInterface::SEQUENCE_ID, $sequenceNo);
    }

    public function getOptionId()
    {
        return $this->getData(UserDefinedFieldOptionInterface::OPTION_ID);
    }

    public function setOptionId($optionId)
    {
        return $this->setData(UserDefinedFieldOptionInterface::OPTION_ID, $optionId);
    }

    public function getValue()
    {
        return $this->getData(UserDefinedFieldOptionInterface::VALUE);
    }

    public function setValue($value)
    {
        return $this->setData(UserDefinedFieldOptionInterface::VALUE, $value);
    }

    public function getDefault()
    {
        return (bool) $this->getData(UserDefinedFieldOptionInterface::DEFAULT);
    }

    public function setDefault($default)
    {
        return $this->setData(UserDefinedFieldOptionInterface::DEFAULT, (bool) $default);
    }
}
