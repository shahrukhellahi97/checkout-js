<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\Data\AttributeSetInterfaceFactory;
use Magento\Eav\Model\AttributeManagement;
use Magento\Eav\Model\AttributeSetManagement;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 *
 * @package Adflex\Payments\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory
     */
    protected $_attributeSet;
    /**
     * @var \Magento\Eav\Model\AttributeManagement
     */
    protected $_attributeManagement;
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $_eavSetupFactory;
    /**
     * @var \Magento\Eav\Model\AttributeSetManagement
     */
    protected $_attributeSetManagement;
    /**
     * @var \Magento\Eav\Model\Entity\Attribute\SetFactory
     */
    protected $_attributeSetFactory;
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;
    /**
     * @var \Magento\Eav\Api\Data\AttributeSetInterfaceFactory
     */
    protected $_attributeSetInterface;

    /**
     * UpgradeData constructor.
     *
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $collectionFactory
     * @param \Magento\Eav\Model\AttributeManagement $attributeManagement
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Magento\Eav\Model\Entity\Attribute\SetFactory $setFactory
     * @param \Magento\Eav\Model\Config $config
     * @param \Magento\Eav\Model\AttributeSetManagement $attributeSetManagement
     * @param \Magento\Eav\Api\Data\AttributeSetInterfaceFactory $attributeSetInterfaceFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        AttributeManagement $attributeManagement,
        EavSetupFactory $eavSetupFactory,
        SetFactory $setFactory,
        Config $config,
        AttributeSetManagement $attributeSetManagement,
        AttributeSetInterfaceFactory $attributeSetInterfaceFactory
    ) {
        $this->_attributeSet = $collectionFactory;
        $this->_attributeManagement = $attributeManagement;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_attributeSetManagement = $attributeSetManagement;
        $this->_attributeSetFactory = $setFactory;
        $this->_eavConfig = $config;
        $this->_attributeSetInterface = $attributeSetInterfaceFactory;
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Zend_Validate_Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);
        // Level 3 tax data for line items.
        $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
        $attributeGroup = 'Level 3 Tax Data';
        // Add new attribute group to all attribute sets.
        foreach ($attributeSetIds as $attributeSetId) {
            $eavSetup->addAttributeGroup(
                $entityTypeId,
                $attributeSetId,
                $attributeGroup,
                250
            );
        }
        // Add new required attributes for level 3 data requirements.
        $attributes = [
            'commodity_code' => [
                'group' => $attributeGroup,
                'input' => 'text',
                'type' => 'varchar',
                'label' => 'Commodity Code',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'visible_in_advanced_search' => false,
                'is_html_allowed_on_front' => false,
                'used_for_promo_rules' => true,
                'is_used_in_grid' => 1,
                'is_visible_in_grid' => 1,
                'is_filterable_in_grid' => 1,
                'frontend_class' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'unique' => false,
                'apply_to' => 'simple,grouped,configurable,downloadable,virtual,bundle'
            ],
            'tax_category' => [
                'group' => $attributeGroup,
                'type' => 'varchar',
                'frontend' => '',
                'label' => 'Tax Category',
                'input' => 'select',
                'class' => '',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'source' => 'Adflex\Payments\Model\Adminhtml\Source\TaxCategory',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 'Standard',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ],
            'tax_type' => [
                'group' => $attributeGroup,
                'type' => 'varchar',
                'frontend' => '',
                'label' => 'Tax Type',
                'input' => 'select',
                'class' => '',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'source' => 'Adflex\Payments\Model\Adminhtml\Source\TaxType',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 'VAT',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ],
            'tax_code' => [
                'group' => $attributeGroup,
                'type' => 'varchar',
                'frontend' => '',
                'label' => 'Tax Code',
                'input' => 'select',
                'class' => '',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'source' => 'Adflex\Payments\Model\Adminhtml\Source\TaxCode',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 'Standard',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        ];

        // Create the attributes.
        foreach ($attributes as $attributeCode => $attributeOptions) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $attributeCode,
                $attributeOptions
            );
        }

        $i = 100;
        // Add the attributes to each attribute group and linked attribute set.
        foreach ($attributeSetIds as $attributeSetId) {
            if ($attributeSetId) {
                foreach (['commodity_code', 'tax_category', 'tax_type', 'tax_code'] as $attributeCode) {
                    $groupId = $eavSetup->getAttributeGroupId(Product::ENTITY, $attributeSetId, $attributeGroup);
                    $this->_attributeManagement->assign(
                        Product::ENTITY,
                        $attributeSetId,
                        $groupId,
                        $attributeCode,
                        (20 + $i)
                    );
                }
            }
        }

        // Finish
        $setup->endSetup();
    }
}
