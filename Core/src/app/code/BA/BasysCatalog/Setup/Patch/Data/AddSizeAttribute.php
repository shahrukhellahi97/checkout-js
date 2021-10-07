<?php
namespace BA\BasysCatalog\Setup\Patch\Data;

use BA\BasysCatalog\Import\Product\Modifier\Action\Util\SizeRegistry;
use BA\BasysCatalog\Setup\Patch\AbstractAttribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddSizeAttribute extends AbstractAttribute
{
    /**
     * @var \BA\BasysCatalog\Import\Product\Modifier\Action\Util\SizeRegistry
     */
    protected $sizeRegistry;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        SizeRegistry $sizeRegistry
    ) {
        parent::__construct($moduleDataSetup, $eavSetupFactory);

        $this->sizeRegistry = $sizeRegistry;
    }

    public function apply()
    {
        $this->create('ba_product_size', [
            'type' => 'varchar',
            'input' => 'select',
            'label' => 'Size',
            'group' => 'Attributes',
            'visible' => false,
            'searchable' => true,
            'filterable' => true,
            'comparable' => true,
            'visible_on_front' => true,
            'filterable_in_search' => true,
            'visible_on_front' => true,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
        ]);

        /** @var \Magento\Eav\Setup\EavSetup $eav */
        $eav = $this->getEavSetup();

        $attributeId = $eav->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'ba_product_size');

        $eav->addAttributeOption([
            'values' => $this->sizeRegistry->getAllSizes(),
            'attribute_id' => $attributeId
        ]);
    }

    public function revert()
    {
        $this->remove([
            'ba_product_size'
        ]);
    }
}
