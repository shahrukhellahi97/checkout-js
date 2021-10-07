<?php
namespace BA\Enquiries\Model\Field;

use BA\Enquiries\Api\Data\EnquiryFieldInterface;
use BA\Enquiries\Api\Data\EnquiryFieldTypeInterface;
use BA\Enquiries\Api\Data\EnquiryInterface;
use Magento\Framework\ObjectManagerInterface;

class Renderer implements RendererInterface
{
    const FIELD_TYPES = [
        EnquiryFieldTypeInterface::TYPE_INPUT => \BA\Enquiries\Model\Field\Type\Input::class,
        EnquiryFieldTypeInterface::TYPE_TEXT => \BA\Enquiries\Model\Field\Type\Textarea::class,
        EnquiryFieldTypeInterface::TYPE_INPUT_EMAIL => \BA\Enquiries\Model\Field\Type\Email::class,
        EnquiryFieldTypeInterface::TYPE_INPUT_DATE => \BA\Enquiries\Model\Field\Type\Date::class,
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    public function toHtml(EnquiryFieldInterface $enquiry)
    {
        if (isset(self::FIELD_TYPES[$enquiry->getType()])) {
            /** @var \BA\Enquiries\Model\Field\RendererInterface $renderer */
            $renderer = $this->objectManager->get(
                self::FIELD_TYPES[$enquiry->getType()]
            );

            return $renderer->toHtml($enquiry);
        }
    }
}