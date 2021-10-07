<?php
namespace BA\BasysCatalog\Setup\Patch\Data;

use BA\BasysCatalog\Setup\Patch\AbstractAttribute;

class AddVariantNameAttribute extends AbstractAttribute
{
    public function apply()
    {
        $this->create('ba_product_variant', [
            'label' => 'Variant'
        ]);
    }

    public function revert()
    {
        $this->remove([
            'ba_product_variant'
        ]);
    }
}
