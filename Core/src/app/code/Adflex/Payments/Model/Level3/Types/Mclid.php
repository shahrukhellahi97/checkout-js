<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Level3\Types;

use Magento\Directory\Model\CountryFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Mclid
 *
 * @package Adflex\Payments\Model\Level3\Types
 */
class Mclid
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Mclid constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CountryFactory $countryFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_countryFactory = $countryFactory;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param $order
     * Enhanced data for MCLID based cards.
     * @return mixed
     */
    public function generateSpecificData($order)
    {
        $shippingAddress = $order->getShippingAddress();
        $country = $this->_countryFactory->create()->loadByCode($shippingAddress->getCountryId());
        $storeId = $this->_storeManager->getStore()->getId();
        $commodityCodeAttribute = $this->_scopeConfig->getValue(
            'payment/adflex/commodity_code',
            ScopeInterface::SCOPE_STORE
        );
        $currency = $this->_scopeConfig->getValue(
            'payment/adflex/base_currency',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $isBase = ($currency == 'base');
        $taxAmount = ($isBase) ? $order->getBaseTaxAmount() : $order->getTaxAmount();
        $shippingAmount = ($isBase) ? $order->getBaseShippingAmount() : $order->getShippingAmount();
        // Header information
        $result['transactionDetails']['enhancedData']['header'] =
            [
                'discount' => '0.00',
                'originalOrderDate' => date('Y-m-d\TH:i:s\Z', strtotime('now')),
                'invoiceNumber' => $order->getQuoteId(),
                'tax' => $taxAmount,
                'taxStatus' => ($taxAmount > 0) ? 'VAT' : 'NonVAT',
                'buyersRef' => $order->getIncrementId(),
                'costCentre' => '0.00',
                'destinationCountryCode' => $country->getData('iso3_code'),
                'destinationPostcode' => $shippingAddress->getPostcode(),
                'freight' => '0.00', //number_format($order->getBaseShippingAmount(), 2),
                'freightTaxRate' => '0.00', //$order->getBaseShippingTaxAmount(),
                'originalDocNumber' => '',
                'shipFromPostcode' => $this->_scopeConfig->getValue(
                    'general/store_information/postcode',
                    ScopeInterface::SCOPE_STORE
                ),
                'supplierOrderRef' => $order->getIncrementId(),
                'vatNumber' => $this->_scopeConfig->getValue(
                    'general/store_information/merchant_vat_number',
                    ScopeInterface::SCOPE_STORE
                )
            ];

        // Line items
        $shippingApplied = false;
        foreach ((array)$order->getAllItems() as $item) {
            if ($item->getProductType() !== 'configurable'
                && $item->getProductType() !== 'bundle') {
                // Need to handle grouped/bundle simple products slightly differently, send as a total of the row.
                if ($item->getProductType() == 'grouped' || !is_null($item->getRowTotalInclTax())) {
                    $itemPriceInclTax = ($isBase) ? $item->getBaseRowTotalInclTax() : $item->getRowTotalInclTax();
                    $itemPrice = ($isBase) ? $item->getBaseRowTotal() : $item->getRowTotal();
                    $item->setPriceInclTax($itemPriceInclTax);
                    $item->setPrice($itemPrice);
                    $item->setQtyOrdered(1);
                }
                $price = (!is_null($item->getPriceInclTax())) ? $item->getPriceInclTax() : $item->getPrice();
                $itemDiscountAmount = ($isBase) ? $item->getBaseDiscountAmount() : $item->getDiscountAmount();
                $commodityCode = (is_null($item->getProduct()->getData($commodityCodeAttribute)))
                    ? '0000'
                    : $item->getProduct()->getData($commodityCodeAttribute);
                // Mastercard have not implemented the freight attribute and is reserved,
                // we have to add to one of the line items instead.
                if (!$shippingApplied) {
                    $unitPrice = number_format($price + ($shippingAmount / $item->getQtyOrdered()), 2);
                    $lineSubtotal = number_format((($price - $itemDiscountAmount) * $item->getQtyOrdered() + $shippingAmount), 2);
                    $shippingApplied = true;
                } else {
                    $unitPrice = number_format($price, 2);
                    $lineSubtotal = number_format((($price - $itemDiscountAmount) * $item->getQtyOrdered()), 2);
                }
                $result['transactionDetails']['enhancedData']['lines'][] = [
                    'commodityCode' => $commodityCode,
                    'lineSubtotal' => $lineSubtotal,
                    'quantity' => $item->getQtyOrdered(),
                    'supplierPartDescription' => 'MAGE-PRODUCT',
                    'taxRate' => number_format($item->getTaxPercent(), 2),
                    'unitOfMeasure' => 'each',
                    'unitPrice' => $unitPrice,
                    'discount' => number_format($itemDiscountAmount, 2),
                    'supplierPartNumber' => $item->getProduct()->getId()
                ];
            }
        }

        return $result;
    }
}
