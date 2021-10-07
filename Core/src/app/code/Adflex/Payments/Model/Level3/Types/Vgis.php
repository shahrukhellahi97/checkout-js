<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Level3\Types;

use Magento\Catalog\Api\ProductRepositoryInterfaceFactory;
use Magento\Checkout\Model\Session;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Vgis
 *
 * @package Adflex\Payments\Model\Level3\Types
 */
class Vgis
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterfaceFactory
     */
    protected $_productRepositoryFactory;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * Mclid constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryInterfaceFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        CountryFactory $countryFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductRepositoryInterfaceFactory $productRepositoryInterfaceFactory,
        Session $checkoutSession
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_countryFactory = $countryFactory;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_productRepositoryFactory = $productRepositoryInterfaceFactory;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * @param $order
     * Enhanced data for VGIS based cards.
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function generateSpecificData($order)
    {
        $shippingAddress = $order->getShippingAddress();
        $country = $this->_countryFactory->create()->loadByCode($shippingAddress->getCountryId());
        $storeId = $this->_storeManager->getStore()->getId();
        $currency = $this->_scopeConfig->getValue(
            'payment/adflex/base_currency',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $commodityCodeAttribute = $this->_scopeConfig->getValue(
            'payment/adflex/commodity_code',
            ScopeInterface::SCOPE_STORE
        );
        $taxCategoryAttribute = $this->_scopeConfig->getValue(
            'payment/adflex/tax_category',
            ScopeInterface::SCOPE_STORE
        );
        $taxCodeAttribute = $this->_scopeConfig->getValue(
            'payment/adflex/tax_code',
            ScopeInterface::SCOPE_STORE
        );
        $taxTreatment = $this->_scopeConfig->getValue(
            'payment/adflex/tax_treatment',
            ScopeInterface::SCOPE_STORE
        );
        $invoiceTreatment = $this->_scopeConfig->getValue(
            'payment/adflex/invoice_treatment',
            ScopeInterface::SCOPE_STORE
        );
        $sectorType = $this->_scopeConfig->getValue(
            'payment/adflex/sector_type',
            ScopeInterface::SCOPE_STORE
        );

        $result['transactionDetails']['merchantNumber'] = '';
        $result['transactionDetails']['terminalNumber'] = null;
        $result['transactionDetails']['reference'] = $order->getIncrementId();
        $result['transactionDetails']['originalTransactionGUID'] = null;

        $adflexData = $this->_checkoutSession->getAdflexData();
        $isBase = ($currency == 'base');
        $taxAmount = ($isBase) ? $order->getBaseTaxAmount() : $order->getTaxAmount();
        $grandTotal = ($isBase) ? $order->getBaseGrandTotal() : $order->getGrandTotal();
        $discount = ($isBase) ? $order->getBaseDiscountAmount() : $order->getDiscountAmount();
        $subTotal = ($isBase) ? $order->getBaseSubtotal() : $order->getSubtotal();
        $shippingAmount = ($isBase) ? $order->getBaseShippingAmount() : $order->getShippingAmount();
        $totalDue = ($isBase) ? $order->getBaseTotalDue() : $order->getTotalDue();
        // Header information
        $result['transactionDetails']['enhancedData']['header'] =
            [
                'discount' => number_format(abs($discount), 2),
                'discountTreatment' => (abs($discount) > 0) ? 'TN' : 'NoDiscount',
                'gross' => number_format($grandTotal, 2),
                'invoiceDate' => date('Y-m-d\TH:i:s\Z', strtotime('now')),
                'invoiceTreatment' => $invoiceTreatment,
                'invoiceNumber' => $order->getIncrementId(),
                'invoiceType' => 'Invoice',
                'tax' => number_format($taxAmount, 2),
                'net' => number_format(($subTotal - abs($discount)) + $shippingAmount, 2),
                'sectorType' => $sectorType,
                'taxPointDate' => date('Y-m-d\TH:i:s\Z', strtotime('now')),
                'taxTreatment' => ($taxAmount == 0) ? 'NIL' : $taxTreatment,
                'buyersRef' => $order->getIncrementId(),
                'costCentre' => '0.00',
                'orderDate' => date('Y-m-d\TH:i:s\Z', strtotime('now')),
                'originalInvoiceNumber' => '',
                'poNumber' => $order->getIncrementId(),
            ];

        // Line items, need tax code + category for later on. Get last item for this data.
        $taxCode = $taxCategory = null;
        $i = 1;
        $shippingApplied = false;
        foreach ((array)$order->getAllVisibleItems() as $item) {
            if ($item->getProductType() !== 'configurable' && $item->getProductType() !== 'bundle') {
                // Need to handle grouped/bundle simple products slightly differently, send as a total of the row.
                $itemTaxAmount = ($isBase) ? $item->getBaseTaxAmount() : $item->getTaxAmount();
                $itemDiscountAmount = ($isBase) ? $item->getBaseDiscountAmount() : $item->getDiscountAmount();
                if ($item->getProductType() == 'grouped' || !is_null($item->getRowTotalInclTax())) {
                    $itemPriceInclTax = ($isBase) ? $item->getBaseRowTotalInclTax() : $item->getRowTotalInclTax();
                    $itemPrice = ($isBase) ? $item->getBaseRowTotal() : $item->getRowTotal();
                    $itemDiscountAmount = ($isBase) ? $item->getBaseDiscountAmount() : $item->getDiscountAmount();
                    $item->setPriceInclTax($itemPriceInclTax);
                    $item->setPrice($itemPrice);
                    $item->setQtyOrdered(1);
                }
                $product = $this->_productRepositoryFactory->create()->getById($item->getProduct()->getId());
                $price = (!is_null($item->getPriceInclTax())) ? $item->getPriceInclTax() : $item->getPrice();
                $commodityCode = (is_null($product->getData($commodityCodeAttribute)))
                    ? '0000'
                    : $product->getData($commodityCodeAttribute);
                $taxCode = (is_null($product->getData($taxCodeAttribute)) && $itemTaxAmount == 0)
                    ? 'Zero'
                    : $product->getData($taxCodeAttribute);
                $taxCategory = (is_null($product->getData($taxCategoryAttribute)) && $itemTaxAmount == 0)
                    ? 'Zero'
                    : $product->getData($taxCategoryAttribute);
                $totalTaxCategories[] = $taxCategory;
                if (!$shippingApplied) {
                    $unitPrice = number_format(($price + ($shippingAmount / $item->getQtyOrdered())), 2);
                    $lineSubtotal = number_format((($price * $item->getQtyOrdered()) + $shippingAmount), 2);
                    $taxableAmount = number_format((($item->getPrice() - abs($itemDiscountAmount)) * $item->getQtyOrdered()) + $order->getBaseShippingAmount(), 2);
                    $shippingApplied = true;
                } else {
                    $unitPrice = number_format($price, 2);
                    $lineSubtotal = number_format(($price * $item->getQtyOrdered()), 2);
                    $taxableAmount = number_format((($item->getPrice() - abs($itemDiscountAmount)) * $item->getQtyOrdered()), 2);
                }
                $result['transactionDetails']['enhancedData']['lines'][] = [
                    'isFreightLine' => 0,
                    'discountType' => 'Value',
                    'commodityCode' => $commodityCode,
                    'commodityDescription' => 'MAGE-PRODUCT',
                    'lineSubtotal' => $lineSubtotal,
                    'quantity' => number_format($item->getQtyOrdered(), 2),
                    'supplierPartDescription' => 'MAGE-PRODUCT',
                    'taxableAmount' => $taxableAmount,
                    'taxAmount' => ($taxTreatment == 'NIL') ? '0.00' : number_format($itemTaxAmount, 2),
                    'taxCategory' => $taxCategory,
                    'taxType' => 'VAT',
                    'buyerPartNumber' => '',
                    'buyerPartDescription' => '',
                    'poLineNumber' => $i,
                    'taxRate' => number_format($item->getTaxPercent(), 2),
                    'unitPricePreDiscount' => number_format(($item->getPrice() + abs($itemDiscountAmount)), 2),
                    'unitOfMeasure' => 'each',
                    'unitPrice' => $unitPrice,
                    'discount' => number_format($itemDiscountAmount, 2),
                    'supplierPartNumber' => $item->getSku(),
                    'taxCode' => ''
                ];

                $i++;
            }
        }

        // Tax summary, uses last tax category + tax code.
        $result['transactionDetails']['enhancedData']['taxSummaries'][] = [
            'discount' => '0.00',
            'taxableAmount' => $totalDue,
            'taxAmount' => ($taxTreatment == 'NIL') ? '0.00' : number_format($taxAmount, 2),
            'taxCategory' => $taxCategory,
            'taxRate' => number_format($order->getTaxPercent(), 2),
            'taxCode' => ''
        ];

        // Address details for supplier.
        $result['transactionDetails']['enhancedData']['supplier'] = [
            'name' => $this->_scopeConfig->getValue(
                'general/store_information/name',
                ScopeInterface::SCOPE_STORE
            ),
            'contactDepartment' => 'Sales',
            'contactPhone' => $this->_scopeConfig->getValue(
                'general/store_information/phone',
                ScopeInterface::SCOPE_STORE
            ),
            'contactName' => substr($this->_scopeConfig->getValue(
                'trans_email/ident_sales/name',
                ScopeInterface::SCOPE_STORE
            ), 0, 49),
            'contactEmail' => substr($this->_scopeConfig->getValue(
                'trans_email/ident_sales/email',
                ScopeInterface::SCOPE_STORE
            ), 0, 49),
            'street1' => $this->_scopeConfig->getValue(
                'general/store_information/street_line1',
                ScopeInterface::SCOPE_STORE
            ),
            'street2' => $this->_scopeConfig->getValue(
                'general/store_information/street_line2',
                ScopeInterface::SCOPE_STORE
            ),
            'street3' => '',
            'street4' => '',
            'county' => $this->_scopeConfig->getValue(
                'general/store_information/region_id',
                ScopeInterface::SCOPE_STORE
            ),
            'city' => $this->_scopeConfig->getValue(
                'general/store_information/city',
                ScopeInterface::SCOPE_STORE
            ),
            'countryName' => $this->_countryFactory->create()
                ->loadByCode($this->_scopeConfig->getValue(
                    'general/store_information/country_id',
                    ScopeInterface::SCOPE_STORE
                ))->getName(),
            'vatNumber' => $this->_scopeConfig->getValue(
                'general/store_information/vat_number',
                ScopeInterface::SCOPE_STORE
            ) ?: 'GB111111111',
            'webURL' => $this->_storeManager->getStore()->getBaseUrl(),
            'postCode' => $this->_scopeConfig->getValue(
                'general/store_information/postcode',
                ScopeInterface::SCOPE_STORE
            ),
        ];

        $company = (!is_null($shippingAddress->getCompany()))
            ? $shippingAddress->getCompany() :
            $shippingAddress->getFirstname() . ' ' . $shippingAddress->getLastname();
        $result['transactionDetails']['enhancedData']['buyer'] = [
            'city' => substr($shippingAddress->getCity(), 0, 49),
            'contactName' => substr($shippingAddress->getFirstname() . ' ' . $shippingAddress->getLastname(), 0, 49),
            'countryName' => $country->getName(),
            'name' => substr($company, 0, 49),
            'street1' => substr($shippingAddress->getStreet()[0], 0, 49),
            'county' => substr($shippingAddress->getRegion(), 0, 49),
            'postCode' => substr($shippingAddress->getPostcode(), 0, 49),
            'vatNumber' => $shippingAddress->getVatId()
        ];

        if (isset($shippingAddress->getStreet()[1])) {
            $result['transactionDetails']['enhancedData']['buyer']['street2'] =
                substr($shippingAddress->getStreet()[1], 0, 49);
        }

        $result['transactionDetails']['enhancedData']['deliveryPoint'] = [
            'city' => substr($shippingAddress->getCity(), 0, 49),
            'countryName' => $country->getName(),
            'name' => substr($company, 0, 49),
            'street1' => substr($shippingAddress->getStreet()[0], 0, 49),
            'county' => $shippingAddress->getRegion(),
            'postCode' => $shippingAddress->getPostcode()
        ];

        if (isset($shippingAddress->getStreet()[1])) {
            $result['transactionDetails']['enhancedData']['deliveryPoint']['street2'] =
                substr($shippingAddress->getStreet()[1], 0, 49);
        }

        $result['transactionDetails']['enhancedData']['supplierHeadOffice'] = [
            'city' => $this->_scopeConfig->getValue(
                'general/store_information/city',
                ScopeInterface::SCOPE_STORE
            ),
            'countryName' => $this->_countryFactory->create()
                ->loadByCode($this->_scopeConfig->getValue(
                    'general/store_information/country_id',
                    ScopeInterface::SCOPE_STORE
                ))->getName(),
            'name' => $this->_scopeConfig->getValue(
                'general/store_information/name',
                ScopeInterface::SCOPE_STORE
            ),
            'street1' => $this->_scopeConfig->getValue(
                'general/store_information/street_line1',
                ScopeInterface::SCOPE_STORE
            ),
            'street2' => $this->_scopeConfig->getValue(
                'general/store_information/street_line2',
                ScopeInterface::SCOPE_STORE
            ),
            'postCode' => $this->_scopeConfig->getValue(
                'general/store_information/postcode',
                ScopeInterface::SCOPE_STORE
            )
        ];

        return $result;
    }
}
