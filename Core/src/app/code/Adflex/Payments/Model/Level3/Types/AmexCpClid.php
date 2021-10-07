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
 * Class AmexCpClid
 *
 * @package Adflex\Payments\Model\Level3\Types
 */
class AmexCpClid
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
     * Enhanced data for AmexCpClid based cards.
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function generateSpecificData($order)
    {
        $shippingAddress = $order->getShippingAddress();
        $country = $this->_countryFactory->create()->loadByCode($shippingAddress->getCountryId());
        $commodityCodeAttribute = $this->_scopeConfig->getValue(
            'payment/adflex/level3/commodity_code',
            ScopeInterface::SCOPE_STORE
        );
        $taxCodeAttribute = $this->_scopeConfig->getValue(
            'payment/adflex/tax_code',
            ScopeInterface::SCOPE_STORE
        );
        $storeId = $this->_storeManager->getStore()->getId();
        $currency = $this->_scopeConfig->getValue(
            'payment/adflex/base_currency',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $isBase = ($currency == 'base');
        $taxAmount = ($isBase) ? $order->getBaseTaxAmount() : $order->getTaxAmount();
        $discount = ($isBase) ? $order->getBaseDiscountAmount() : $order->getDiscountAmount();
        $shippingAmount = ($isBase) ? $order->getBaseShippingAmount() : $order->getShippingAmount();
        // Header information
        $result['transactionDetails']['enhancedData']['header'] =
            [
                'discount' => $discount,
                'originalOrderDate' => date('Y-m-d\TH:i:s\Z', strtotime('now')),
                'invoiceNumber' => $order->getQuoteId(),
                'tax' => number_format($taxAmount, 2),
                'taxStatus' => ($taxAmount > 0) ? 'VAT' : 'NonVAT',
                'customerRef' => $order->getIncrementId(),
                'costCentre' => '0.00',
                'destinationCountryCode' => $country->getData('iso3_code'),
                'destinationPostcode' => $shippingAddress->getPostcode(),
                // Freight are currently reserved values to be used.
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
        $i = 0;
        // We only want the constituent simple children to build up the electronic invoice,
        // but allow virtual/downloadable through.
        $disallowedTypes = ['configurable', 'bundle'];
        foreach ((array)$order->getAllItems() as $item) {
            // Need to handle grouped/bundle simple products slightly differently, send as a total of the row.
            if ($item->getProductType() == 'grouped' || !is_null($item->getRowTotalInclTax())) {
                $itemPriceInclTax = ($isBase) ? $item->getBaseRowTotalInclTax() : $item->getRowTotalInclTax();
                $itemPrice = ($isBase) ? $item->getBaseRowTotal() : $item->getRowTotal();
                $item->setPriceInclTax($itemPriceInclTax);
                $item->setPrice($itemPrice);
                $item->setQtyOrdered(1);
            }
            if (!in_array($item->getProductType(), $disallowedTypes)) {
                $itemTaxAmount = ($isBase) ? $item->getBaseTaxAmount() : $item->getTaxAmount();
                $itemDiscountAmount = ($isBase) ? $item->getBaseDiscountAmount() : $item->getDiscountAmount();
                $price = (!is_null($item->getPriceInclTax())) ? $item->getPriceInclTax() : $item->getPrice();
                $commodityCode = (is_null($item->getProduct()->getData($commodityCodeAttribute)))
                    ? '0000'
                    : $item->getProduct()->getData($commodityCodeAttribute);
                $taxCode = (is_null($item->getProduct()->getData($taxCodeAttribute)) && $itemTaxAmount == 0)
                    ? 'Zero'
                    : $item->getProduct()->getData($taxCodeAttribute);
                // Amex have not implemented the freight attribute and is reserved,
                // we have to add to one of the line items instead.
                if (!$shippingApplied) {
                    $unitPrice = number_format(($price - $itemDiscountAmount + ($shippingAmount / $item->getQtyOrdered())), 2);
                    $lineSubtotal = number_format((($price - $itemDiscountAmount * $item->getQtyOrdered()) + $shippingAmount), 2);
                    $shippingApplied = true;
                } else {
                    $unitPrice = number_format($price - $itemDiscountAmount, 2);
                    $lineSubtotal = number_format((($price - $itemDiscountAmount) * $item->getQtyOrdered()), 2);
                }
                $result['transactionDetails']['enhancedData']['lines'][] = [
                    'commodityCode' => $commodityCode,
                    'lineSubtotal' => $lineSubtotal,
                    'quantity' => $item->getQtyOrdered(),
                    'supplierPartDescription' => 'MAGE-PRODUCT',
                    'taxAmount' => number_format($itemTaxAmount, 2),
                    'taxRate' => number_format($item->getTaxPercent(), 2),
                    'unitOfMeasure' => 'each',
                    'unitPrice' => $unitPrice,
                    'discount' => number_format($itemDiscountAmount, 2),
                    'supplierPartNumber' => $item->getProduct()->getId(),
                    'taxCode' => $taxCode
                ];

                // If the tax code is set, then send the information along.
                if (!is_null($item->getProduct()->getData($taxCodeAttribute))) {
                    $result['transactionDetails']['enhancedData']['lines'][$i]['taxCode']
                        = $item->getProduct()->getData($taxCodeAttribute);
                // If the line item doesn't have a tax code set, something default to get us over the line.
                } elseif ($item->getTaxAmount() > 0) {
                    $result['transactionDetails']['enhancedData']['lines'][$i]['taxCode'] = 'STANDARD';
                } else {
                    $result['transactionDetails']['enhancedData']['lines'][$i]['taxCode'] = 'Exempt';
                }

                $i++;
            }
        }

        // Address details for purchase.
        $result['transactionDetails']['enhancedData']['purchasingAddressFormat'] = [
            'shipFromAdd1' => $this->_scopeConfig->getValue(
                'general/store_information/street_line1',
                ScopeInterface::SCOPE_STORE
            ),
            'shipFromAdd2' => $this->_scopeConfig->getValue(
                'general/store_information/street_line2',
                ScopeInterface::SCOPE_STORE
            ),
            'shipFromCountryName' => $this->_scopeConfig->getValue(
                'general/store_information/country_id',
                ScopeInterface::SCOPE_STORE
            ),
            'shipFromEuCountry' => ($this->isInEu($this->_scopeConfig->getValue(
                'general/store_information/country_id',
                ScopeInterface::SCOPE_STORE
            ))) ? 'Y' : 'N',
            'shipFromPostcode' => $this->_scopeConfig->getValue(
                'general/store_information/postcode',
                ScopeInterface::SCOPE_STORE
            ),
            'shipToAdd1' => $shippingAddress->getStreet(1)[0],
            'shipToAdd2' => $shippingAddress->getStreet(2)[0],
            'shipToAdd3' => $shippingAddress->getStreet(3)[0],
            'shipToPostcode' => $shippingAddress->getPostcode(),
            'shipToCountryName' => $shippingAddress->getCountryId(),
            'shipToEUCountry' => ($this->isInEu($shippingAddress->getCountryId())) ? 'Y' : 'N'
        ];

        return $result;
    }

    /**
     * @param $countryCode
     * @return bool
     * Returns a yes or no if the country code provided is in the EU.
     */
    private function isInEu($countryCode)
    {
        $euCountries = [
            'AL', 'AD', 'AM', 'AT', 'BY', 'BE', 'BA', 'BG', 'CH', 'CY', 'CZ', 'DE',
            'DK', 'EE', 'ES', 'FO', 'FI', 'FR', 'GB', 'GE', 'GI', 'GR', 'HU', 'HR',
            'IE', 'IS', 'IT', 'LI', 'LT', 'LU', 'LV', 'MC', 'MK', 'MT', 'NO', 'NL', 'PL',
            'PT', 'RO', 'RU', 'SE', 'SI', 'SK', 'SM', 'TR', 'UA', 'VA'
        ];

        return in_array($countryCode, $euCountries);
    }
}
