<?php
namespace BA\Theme\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Catalog\Model\Config;

class AddMoreStockDueProductAttribute implements DataPatchInterface, PatchRevertableInterface
{

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory,
        Config $config
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'more_stock_due',
            [
                'type' => 'varchar',
                'label' => 'More Stock Due',
                'input' => 'text',
                'source' => '',
                'frontend' => '',
                'required' => false,
                'backend' => '',
                'sort_order' => '2',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'default' => null,
                'visible' => true,
                'user_defined' => true,
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'unique' => false,
                'apply_to' => '',
                'used_in_product_listing' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'option' => ['values' => []]
            ]
        );

        // get default attribute set id
        $attributeSetId = $categorySetup->getDefaultAttributeSetId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeGroupName = 'Layout Customizations';
    
        $groupId = $this->config->getAttributeGroupId($attributeSetId, $attributeGroupName);
        if ($groupId > 0) {
                // add attribute to group
                $categorySetup->addAttributeToGroup(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attributeSetId,
                    $attributeGroupName, // attribute group
                    'more_stock_due', // attribute code
                    2 // sort order
                );
        } else {
                // your custom attribute group/tab
                $categorySetup->addAttributeGroup(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attributeSetId,
                    $attributeGroupName, // attribute group name
                    100 // sort order
                );
    
                // add attribute to group
                $categorySetup->addAttributeToGroup(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attributeSetId,
                    $attributeGroupName, // attribute group
                    'more_stock_due', // attribute code
                    2 // sort order
                );
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'more_stock_due');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [
        
        ];
    }
}

