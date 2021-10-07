<?php
namespace BA\BasysCatalog\Model\Config\Structure\Field;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Framework\Module\ModuleListInterface;

class Data extends AbstractDynamicField
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $helper;

    public function __construct(
        ModuleListInterface $moduleList,
        BasysStoreManagementInterface $basysStoreManagement,
        \BA\BasysCatalog\Helper\Data $helper,
        $fieldProviders = []
    ) {
        parent::__construct($moduleList, $fieldProviders);

        $this->helper = $helper;
        $this->basysStoreManagement = $basysStoreManagement;
    }

    public function getGroups()
    {
        $groups = [];
        $storeId = $this->helper->getCurrentStoreId();

        /** @var \BA\BasysCatalog\Api\Data\CatalogInterface $catalog */
        foreach ($this->basysStoreManagement->getActiveCatalogs($storeId) as $catalog) {
            $key = 'c' . $catalog->getId();

            $groups[$key] = [
                'id'            => $key,
                'label'         => $catalog->getName() . ' (ID: ' . $catalog->getId() . ')',
                'showInDefault' => '1',
                'showInWebsite' => '1',
                'showInStore'   => '1',
                '_elementType'  => 'group',
                'path'          => $this->getSection(),
                'children'      => $this->process($catalog, $key)
            ];
        }

        return $groups;
    }

    public function getModule()
    {
        return 'BA_BasysCatalog';
    }

    public function getSection()
    {
        return 'basys_catalog';
    }

    public function getTab()
    {
        return 'brandaddition';
    }
}
