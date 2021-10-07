<?php
namespace BA\Enquiries\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

abstract class AbstractViewModel implements ArgumentInterface
{
    /**
     * @var \BA\Enquiries\Helper\Data
     */
    protected $enquiriesHelper;

    /**
     * @var \BA\Enquiries\Model\Field\RendererInterface
     */
    protected $renderer;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \BA\Enquiries\Helper\FormFactory
     */
    protected $formFactory;

    public function __construct(
        \BA\Enquiries\Helper\Data $enquiriesHelper,
        \BA\Enquiries\Helper\FormFactory $formFactory,
        \BA\Enquiries\Model\Field\RendererInterface $renderer,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->enquiriesHelper = $enquiriesHelper;
        $this->formFactory = $formFactory;
        $this->renderer = $renderer;
        $this->urlBuilder = $urlBuilder;
    }

    /**
    * @return \BA\Enquiries\Model\EnquiryField[]
    */
    abstract public function getFields();

    public function renderField(\BA\Enquiries\Model\EnquiryField $enquiryField)
    {
        return $this->renderer->toHtml($enquiryField);
    }
}
