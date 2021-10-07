<?php
namespace BA\BasysCatalog\Setup\Patch;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

abstract class AbstractAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $eavSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return \Magento\Eav\Setup\EavSetup
     */
    public function getEavSetup()
    {
        if ($this->eavSetup == null) {
            $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        }

        return $this->eavSetup;
    }

    public function getAliases()
    {
        return [];
    }

    public function create($code, $data = [])
    {
        $params = array_merge([
            'type' => 'varchar',
            'label' => 'Entity',
            'input' => 'text',
            'source' => '',
            'default' => '',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'used_in_product_listing' => true,
            'user_defined' => true,
            'required' => false,
            'group' => 'BA',
            'sort_order' => 80,
        ], $data);

        $this->getEavSetup()->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $code, $params);
    }

    public function remove($codes)
    {
        foreach ($codes as $code) {
            $this->getEavSetup()->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        }
    }
}
