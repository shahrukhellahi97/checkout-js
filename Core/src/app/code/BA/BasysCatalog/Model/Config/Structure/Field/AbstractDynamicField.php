<?php
namespace BA\BasysCatalog\Model\Config\Structure\Field;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Config\Model\Config\Structure\Data as StructureData;
use Magento\Framework\Module\ModuleListInterface;

abstract class AbstractDynamicField
{
    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $moduleList;

    /**
     * @var \BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface[]|array
     */
    protected $fieldProviders;

    public function __construct(
        ModuleListInterface $moduleList,
        array $fieldProviders = []
    ) {
        $this->moduleList = $moduleList;
        $this->fieldProviders = $fieldProviders;
    }

    public function beforeMerge(StructureData $object, array $config)
    {
        $moduleList = $this->moduleList->getNames();

        foreach ($moduleList as $name) {
            if (strpos($name, $this->getModule()) === false) {
                continue;
            }

            $this->moduleslist[] = $name;
        }

        if (!isset($config['config']['system'])) {
            return [$config];
        }

        $sections = $config['config']['system']['sections'];

        foreach ($sections as $sectionId => $section) {
            if (isset($section['tab']) && ($section['tab'] === $this->getTab()) && ($section['id'] !== $this->getTab())) {
                foreach ($this->moduleslist as $moduleName) {
                    if ($section['id'] !== $this->getSection()) {
                        continue;
                    }
                    
                    try {
                        $dynamicGroups = $this->getGroups();
                    } catch (\Exception $e) {
                        $dynamicGroups = [];
                    }

                    if (!empty($dynamicGroups)) {
                        $config['config']['system']['sections'][$sectionId]['children'] = array_merge(
                            $section['children'],
                            $dynamicGroups
                        );
                    }
                    break;
                }
            }
        }

        return [$config];
    }

    public function getDefaultFields($key)
    {
        return [
            // 'type'          => 'text',
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore'   => '1',
            'sortOrder'     => 10,
            'module_name'   => $this->getModule(),
            // 'validate'      => 'required-entry',
            '_elementType'  => 'field',
            'path'          => $this->getSection() . '/' . $key
        ];
    }

    public function process($object, $key)
    {
        $default = $this->getDefaultFields($key);
        $fields = [];

        /** @var \\BA\BasysCatalog\Model\Config\Structure\Field\FieldProviderInterface $field */
        foreach ($this->fieldProviders as $field) {
            $fieldArray = $field->process($object);

            if (is_array($fieldArray) && !isset($fieldArray['id'])) {
                foreach ($fieldArray as $newField) {
                    $fields[$newField['id']] = $this->mergeArray($default, $newField);
                }
            } else {
                $fields[$fieldArray['id']] = $this->mergeArray($default, $fieldArray);
            }
        }

        $x = 'xxx';

        return $fields;
    }

    private function mergeArray($defaults, $field)
    {
        return array_merge(
            $defaults,
            ['id' => $field['id']],
            $field
        );
    }
    
    /**
     * @return string
     */
    abstract public function getSection();

    /**
     * @return array
     */
    abstract public function getGroups();

    /**
     * @return string
     */
    abstract public function getModule();

    /**
     * @return string
     */
    public function getTab()
    {
        return 'brandaddition';
    }
}