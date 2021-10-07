<?php
namespace BA\UserType\Model;

use BA\UserType\Model\ResourceModel\Rule as ResourceModelRule;
use Magento\Quote\Model\Quote\Address;
use Magento\Rule\Model\AbstractModel;

class Rule extends AbstractModel
{
    /**
     * @var \Magento\CustomerSegment\Model\ConditionFactory
     */
    protected $conditionFactory;

    /**
     * @var \Magento\Rule\Model\Action\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\CustomerSegment\Model\ConditionFactory $conditionFactory,
        \Magento\Rule\Model\Action\CollectionFactory $collectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->conditionFactory = $conditionFactory;
        $this->collectionFactory = $collectionFactory;
        
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }
    
    protected function _construct()
    {
        parent::_construct();
        
        $this->_init(ResourceModelRule::class);
        $this->setIdFieldName('rule_id');
    }

    public function getConditionsInstance()
    {
        return $this->conditionFactory->create(
            \Magento\CustomerSegment\Model\Segment\Condition\Combine\Root::class
        );
    }

    public function getActionsInstance()
    {
        return $this->collectionFactory->create();
    }
}