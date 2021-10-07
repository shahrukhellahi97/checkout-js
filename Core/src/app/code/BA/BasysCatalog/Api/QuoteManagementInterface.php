<?php
namespace BA\BasysCatalog\Api;

interface QuoteManagementInterface
{
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return \Magento\Quote\Model\Quote\Item[]|array
     */
    public function getAllProducts(\Magento\Quote\Model\Quote $quote);

    /**
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return \Magento\Catalog\Model\Product
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProduct(\Magento\Quote\Model\Quote\Item $item);
}
