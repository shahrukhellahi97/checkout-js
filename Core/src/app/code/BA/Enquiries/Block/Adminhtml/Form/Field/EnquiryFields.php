<?php
namespace BA\Enquiries\Block\Adminhtml\Form\Field;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class EnquiryFields extends AbstractFieldArray
{
    /**
     * @var \BA\Enquiries\Block\Adminhtml\Form\Field\BooleanColumn
     */
    protected $booleanRenderer;

    /**
     * @var \BA\Enquiries\Block\Adminhtml\Form\Field\TypeColumn
     */
    protected $typeRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn(EnquiryFieldInterface::LABEL, [
            'label' => 'Label',
            'class' => 'required-entry'
        ]);

        $this->addColumn(EnquiryFieldInterface::TYPE, [
            'label' => 'Type',
            'class' => 'required-entry',
            'renderer' => $this->getTypeRenderer()
        ]);

        $this->addColumn(EnquiryFieldInterface::IS_REQUIRED, [
            'label' => 'Required',
            'class' => 'required-entry',
            'renderer' => $this->getBooleanRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $required = $row->getIsRequiried();

        if ($required !== null) {
            $options['option_' . $this->getBooleanRenderer()->calcOptionHash($required)] = 'selected="selected"';
        }

        $type = $row->getType();

        if ($type !== null) {
            $options['option_' . $this->getTypeRenderer()->calcOptionHash($type)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    public function getTypeRenderer()
    {
        if (!$this->typeRenderer) {
            $this->typeRenderer = $this->getLayout()->createBlock(
                TypeColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->typeRenderer;
    }

    public function getBooleanRenderer()
    {
        if (!$this->booleanRenderer) {
            $this->booleanRenderer = $this->getLayout()->createBlock(
                BooleanColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->booleanRenderer;
    }
}
