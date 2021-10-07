<?php
namespace BA\BasysCatalog\Block\Adminhtml\Form\Field;

use BA\BasysCatalog\Block\Adminhtml\Form\Field\Select\Catalog;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Catalogs extends AbstractFieldArray
{
    /**
     * @var \BA\BasysCatalog\Block\Adminhtml\Form\Field\Select\Catalog
     */
    protected $catalogSelect;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = [],
        \Magento\Framework\View\Helper\SecureHtmlRenderer $secureRenderer = null,
        Catalog $catalogSelect
    ) {
        $this->catalogSelect = $catalogSelect;

        parent::__construct($context, $data, $secureRenderer);
    }

    protected function _prepareToRender()
    {
        $this->addColumn('catalog_id', [
            'label' => __('Catalog'),
            'renderer' => $this->catalogSelect,
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}