<?php
namespace BA\BasysCatalog\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;
use BA\BasysCatalog\Model\Config\Source\KeyGroup;

class LoadKeyGroup extends Select
{
 
    /**
     * @var \BA\BasysCatalog\Model\Config\Source\Catalog
     */
    protected $options;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        array $data = [],
        KeyGroup $options
    ) {
        $this->options = $options;

        parent::__construct($context, $data);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
 
    /**
     * @param $value
     * @return LoadCatalogs
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }
  
    /**
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->options->toOptionArray());
        }
        return parent::_toHtml();
    }
}
