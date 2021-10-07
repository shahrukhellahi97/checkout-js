<?php
namespace BA\BasysCatalog\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class DynamicFields extends AbstractFieldArray
{
 
    protected $catalogRenderer;
    protected $keyGroupRenderer;
    protected $sourceCodeRenderer;
 
    protected function _prepareToRender()
    {
        $this->addColumn('catalog_ids', [
            'label' => __('Catalogs'),
            'renderer' => $this->getCatalogRenderer()
        ]);
        $this->addColumn('source_code', [
            'label' => __('Source Code'),
            'renderer' => $this->getSourceCodeRenderer()
        ]);
        $this->addColumn('key_group', [
            'label' => __('Key Group'),
            'renderer' => $this->getkeyGroupRenderer()
        ]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
 
    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws LocalizedException
     */
    private function getCatalogRenderer()
    {
        if (!$this->catalogRenderer) {
            $this->catalogRenderer = $this->getLayout()->createBlock(
                LoadCatalogs::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->catalogRenderer->setClass('load_catalogs required-entry');
            $this->catalogRenderer->setExtraParams('style="width:200px"');
        }
        return $this->catalogRenderer;
    }
 
    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws LocalizedException
     */
    private function getSourceCodeRenderer()
    {
        if (!$this->sourceCodeRenderer) {
            $this->sourceCodeRenderer = $this->getLayout()->createBlock(
                LoadSourceCode::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->sourceCodeRenderer->setClass('source_code required-entry');
            $this->sourceCodeRenderer->setExtraParams('style="width:200px"');
        }
        return $this->sourceCodeRenderer;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws LocalizedException
     */
    private function getkeyGroupRenderer()
    {
        if (!$this->keyGroupRenderer) {
            $this->keyGroupRenderer = $this->getLayout()->createBlock(
                LoadKeyGroup::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->keyGroupRenderer->setClass('key_group required-entry');
            $this->keyGroupRenderer->setExtraParams('style="width:200px"');
        }
        return $this->keyGroupRenderer;
    }

    /**
     * @param DataObject $row
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $catalog = $row->getCatalogIds();
        if ($catalog !== null) {
            $options['option_' . $this->getCatalogRenderer()->calcOptionHash($catalog)] = 'selected="selected"';
        }
        $keyGroup = $row->getKeyGroup();
        if ($keyGroup !== null) {
            $options['option_' . $this->getkeyGroupRenderer()->calcOptionHash($keyGroup)] = 'selected="selected"';
        }
        $sourceCode = $row->getSourceCode();
        if ($sourceCode !== null) {
            $options['option_' . $this->getSourceCodeRenderer()->calcOptionHash($sourceCode)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}
