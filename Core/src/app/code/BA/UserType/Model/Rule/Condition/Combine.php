<?php
namespace BA\UserType\Model\Rule\Condition;

use BA\UserType\Model\Condition\Combine\AbstractCombine;

class Combine extends AbstractCombine
{
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \BA\UserType\Model\ResourceModel\Rule $ruleResource,
        array $data = []
    ) {
        parent::__construct($context, $ruleResource, $data);
        $this->setType(\BA\UserType\Model\Rule\Condition::class);
    }

    // public function getNewChildSelectOptions()
    // {
    //     $conditions = [
    //         [ // customer wishlist combo
    //             'value' => \Magento\Reminder\Model\Rule\Condition\Wishlist::class,
    //             'label' => __('Wish List'), ],

    //         [ // customer shopping cart combo
    //             'value' => \Magento\Reminder\Model\Rule\Condition\Cart::class,
    //             'label' => __('Shopping Cart')],

    //     ];

    //     $conditions = array_merge_recursive(parent::getNewChildSelectOptions(), $conditions);
    //     return $conditions;
    // }
}