<?php
namespace BA\Enquiries\Model;

use BA\Enquiries\Api\Data\EnquiryInterface;
use BA\Enquiries\Api\Data\EnquiryItemInterface;
use BA\Enquiries\Api\EnquiryManagementInterface;

use Magento\Quote\Model\Quote;

class EnquiryManagement implements EnquiryManagementInterface
{
    /**
     * @var \BA\Enquiries\Model\EnquiryFactory
     */
    protected $enquiryFactory;

    /**
     * @var \BA\Enquiries\Model\EnquiryFactory
     */
    protected $enquiryItemFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \BA\Enquiries\Helper\FormFactory
     */
    protected $formFactory;

    /**
     * @var \BA\Enquiries\Helper\Form\Summary
     */
    protected $summaryHelper;

    public function __construct(
        \BA\Enquiries\Model\EnquiryFactory $enquiryFactory,
        \BA\Enquiries\Model\EnquiryItemFactory $enquiryItemFactory,
        \BA\Enquiries\Helper\FormFactory $formFactory,
        \Magento\Catalog\Helper\Image $imageHelper
    ) {
        $this->enquiryFactory = $enquiryFactory;
        $this->formFactory = $formFactory;
        $this->imageHelper = $imageHelper;
        $this->enquiryItemFactory = $enquiryItemFactory;
    }

    private function getHelper()
    {
        if (!$this->summaryHelper) {
            $this->summaryHelper = $this->formFactory->create(
                \BA\Enquiries\Helper\FormFactory::TYPE_SUMMARY
            );
        }

        return $this->summaryHelper;
    }

    public function create(Quote $object): EnquiryInterface
    {
        /** @var \BA\Enquiries\Model\Enquiry $enquiry */
        $enquiry = $this->enquiryFactory->create();

        $enquiry->setItems($this->getItems($object));

        if ($fields = $this->getHelper()->getFields()) {
            $enquiry->setAdditionalFields($fields);
        }

        return $enquiry;
    }

    private function getItems(Quote $object)
    {
        $result = [];

        /** @var \Magento\Quote\Model\Quote\Item $item*/
        foreach ($object->getAllVisibleItems() as $item) {
            $result[] = $this->enquiryItemFactory->create([
                'data' => [
                    EnquiryItemInterface::SKU => $item->getSku(),
                    EnquiryItemInterface::NAME => $item->getName(),
                    EnquiryItemInterface::QUANTITY => $item->getQty(),
                    EnquiryItemInterface::LINE_ITEM_COST => $item->getPrice(),
                    EnquiryItemInterface::TOTAL => $item->getRowTotal(),
                    EnquiryItemInterface::IMAGE => $this->getImageUrl($item),
                ],
            ]);
        }

        return $result;
    }

    private function getImageUrl(
        \Magento\Quote\Model\Quote\Item $item
    ) {
        $product = $item->getProduct();

        return $this->imageHelper->init($product, 'product_page_image_small')
            ->setImageFile($product->getFile())
            ->resize(100, 100)
            ->getUrl();
    }
}
