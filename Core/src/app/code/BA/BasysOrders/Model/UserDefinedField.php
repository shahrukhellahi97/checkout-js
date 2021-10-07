<?php
namespace BA\BasysOrders\Model;

use BA\BasysOrders\Api\Data\UserDefinedFieldInterface;
use BA\BasysOrders\Model\ResourceModel\UserDefinedField as ResourceModelUserDefinedField;
use Magento\Framework\Model\AbstractModel;

class UserDefinedField extends AbstractModel implements UserDefinedFieldInterface
{
    /**
     * @var \BA\BasysOrders\Model\UserDefinedFieldOptionFactory
     */
    protected $userDefinedFieldOptionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        UserDefinedFieldOptionFactory $userDefinedFieldOptionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->userDefinedFieldOptionFactory = $userDefinedFieldOptionFactory;
    }

    protected function _construct()
    {
        $this->_init(ResourceModelUserDefinedField::class);
    }

    public function getValue()
    {
        return $this->getData('value');
    }

    public function setValue($value)
    {
        return $this->setData('value', $value);
    }

    public function addOption($value, $isDefault = false)
    {
        $options = $this->getOptions();
        /** @var \BA\BasysOrders\Model\UserDefinedFieldOption $model */
        $model = $this->userDefinedFieldOptionFactory->create();

        $model->setValue($value)
            ->setDefault((bool) $isDefault)
            ->setOptionId(count($options) + 1) // very tricky
            ->setDivisionId($this->getDivisionId())
            ->setSequenceNo($this->getSequenceNo());

        return $this->setOptions(
            array_merge($options, [$model])
        );
    }

    public function setCaption($caption)
    {
        return $this->setData(UserDefinedFieldInterface::CAPTION, $caption);
    }

    public function setRule($rule)
    {
        return $this->setData(UserDefinedFieldInterface::RULE, $rule);
    }

    public function setUppercase($uppercase)
    {
        return $this->setData(UserDefinedFieldInterface::UPPERCASE, (bool) $uppercase);
    }

    public function setOptions($options)
    {
        return $this->setData('options', $options);
    }

    public function getDivisionId()
    {
        return $this->getData(UserDefinedFieldInterface::DIVISION_ID);
    }

    public function setDivisionId($divisionId)
    {
        return $this->setData(UserDefinedFieldInterface::DIVISION_ID, $divisionId);
    }

    public function getSequenceNo()
    {
        return $this->getData(UserDefinedFieldInterface::SEQUENCE_ID);
    }

    public function setSequenceNo($sequenceNo)
    {
        return $this->setData(UserDefinedFieldInterface::SEQUENCE_ID, $sequenceNo);
    }

    public function getCaption()
    {
        return $this->getData(UserDefinedFieldInterface::CAPTION);
    }

    public function getRule()
    {
        return $this->getData(UserDefinedFieldInterface::RULE);
    }

    public function getUppercase()
    {
        return (bool) $this->getData(UserDefinedFieldInterface::UPPERCASE);
    }

    public function getOptions()
    {
        if ($data = $this->getData('options')) {
            return $data;
        }

        return [];
    }
}
