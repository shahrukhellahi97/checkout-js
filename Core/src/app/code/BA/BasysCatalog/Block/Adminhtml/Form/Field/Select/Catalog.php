<?php
namespace BA\BasysCatalog\Block\Adminhtml\Form\Field\Select;

use Magento\Framework\View\Element\Html\Select;

class Catalog extends Select
{
    /**
     * @var \BA\BasysCatalog\Model\Config\Source\Catalog
     */
    protected $options;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        array $data = [],
        \BA\BasysCatalog\Model\Config\Source\Catalog $options
    ) {
        $this->options = $options;

        parent::__construct($context, $data);
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function setInputId($value)
    {
        return $this->setId($value);
    }

    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->options->toOptionArray());
        }
        
        return parent::_toHtml();
    }
}