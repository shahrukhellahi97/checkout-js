<?php
namespace BA\Freight\Block\Adminhtml\Form\Field;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class ValueBand extends AbstractFieldArray
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    public function __construct(
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null,
        BasysStoreManagementInterface $basysStoreManagement
    ) {
        parent::__construct($context, $data, $secureRenderer);

        $this->basysStoreManagement = $basysStoreManagement;
    }

    protected function _prepareToRender()
    {
        $this->addColumn('value_break', ['label' => __('Break'), 'class' => 'required-entry']);
        
        foreach ($this->basysStoreManagement->getAvailableCurrencies() as $currency) {
            $this->addColumn($currency, [
                'label' => strtoupper($currency),
                'class' => 'required-entry'
            ]);
        }

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}