<?php
namespace BA\Enquiries\Model;

use BA\Enquiries\Api\Data\EnquiryItemInterface;
use Magento\Framework\DataObject;

class EnquiryItem extends DataObject implements EnquiryItemInterface
{
    public function getSku()
    {
        return $this->getData(EnquiryItemInterface::SKU);
    }

    public function getName()
    {
        return $this->getData(EnquiryItemInterface::NAME);
    }

    public function getQuantity()
    {
        return $this->getData(EnquiryItemInterface::QUANTITY);
    }

    public function getLineItemCost()
    {
        return $this->getData(EnquiryItemInterface::LINE_ITEM_COST);
    }

    public function getTotal()
    {
        return $this->getData(EnquiryItemInterface::TOTAL);
    }

    public function getImage()
    {
        return $this->getData(EnquiryItemInterface::IMAGE);
    }
}