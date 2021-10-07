<?php
namespace BA\UserType\Model\Rule;

class ConditionFactory
{
    /**
     * @var string[]|array
     */
    protected $conditions = [];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create($type)
    {
        if (in_array($type, $this->conditions)) {
            return $this->objectManager->create($type);
        } else {
            throw new \InvalidArgumentException(__('Condition type is unexpected'));
        }
    }
}