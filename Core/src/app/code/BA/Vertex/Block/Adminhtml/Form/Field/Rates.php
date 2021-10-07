<?php
namespace BA\Vertex\Block\Adminhtml\Form\Field;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use BA\Vertex\Block\Adminhtml\Form\Field\Rates\Checkbox;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Rates extends AbstractFieldArray
{
    /**
     * @var \BA\Vertex\Block\Adminhtml\Form\Field\Rates\Checkbox
     */
    protected $checkbox;

    public function __construct(
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
    }

    protected function _prepareToRender()
    {
        $this->addColumn('sku', [
            'label' => 'SKU',
            'class' => 'required-entry'
        ]);
        
        $this->addColumn('rate', ['label' => __('Rate %'), 'class' => 'required-entry']);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}