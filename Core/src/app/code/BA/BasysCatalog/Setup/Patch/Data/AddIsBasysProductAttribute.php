<?php
namespace BA\BasysCatalog\Setup\Patch\Data;

use BA\BasysCatalog\Setup\Patch\AbstractAttribute;

class AddIsBasysProductAttribute extends AbstractAttribute
{
    public function apply()
    {
        $this->create('ba_is_basys_product', [
            'label' => 'Is Basys Product?',
            'type' => 'int',
            'input' => 'boolean',
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'default' => 0,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
        ]);
    }

    public function revert()
    {
        $this->remove([
            'ba_is_basys_product'
        ]);
    }
}
