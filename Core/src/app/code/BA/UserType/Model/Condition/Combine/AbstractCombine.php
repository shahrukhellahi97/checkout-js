<?php
namespace BA\UserType\Model\Condition\Combine;

abstract class AbstractCombine extends \Magento\Rule\Model\Condition\Combine
{
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \BA\UserType\Model\ResourceModel\Rule $ruleResource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        
        $this->_ruleResource = $ruleResource;
    }
}