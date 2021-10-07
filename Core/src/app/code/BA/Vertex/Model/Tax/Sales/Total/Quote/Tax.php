<?php
namespace BA\Vertex\Model\Tax\Sales\Total\Quote;

use BA\BasysCatalog\Api\ProductResolverInterface;
use BA\BasysCatalog\Api\QuoteManagementInterface;
use BA\Vertex\Api\RateProviderInterface;
use BA\Vertex\Model\Tax\TaxCalculation;
use Magento\Customer\Api\Data\AddressInterfaceFactory as CustomerAddressFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory as CustomerAddressRegionFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Tax\Api\TaxCalculationInterface;

class Tax extends \Magento\Tax\Model\Sales\Total\Quote\Tax
{
    /**
     * @var \BA\Vertex\Model\Tax\TaxCalculation
     */
    protected $taxCalculation;

    /**
     * @var \BA\Vertex\Api\RateProviderInterface
     */
    protected $rateProvider;

    /**
     * @var \BA\Vertex\Api\Data\RateInterface[]|array
     */
    protected $rates;

    /**
     * @var \BA\BasysCatalog\Api\ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var \BA\BasysCatalog\Api\QuoteManagementInterface
     */
    protected $quoteManagement;

    public function __construct(
        \Magento\Tax\Model\Config $taxConfig,
        \Magento\Tax\Api\TaxCalculationInterface $taxCalculationService,
        \Magento\Tax\Api\Data\QuoteDetailsInterfaceFactory $quoteDetailsDataObjectFactory,
        \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $quoteDetailsItemDataObjectFactory,
        \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyDataObjectFactory,
        CustomerAddressFactory $customerAddressFactory,
        CustomerAddressRegionFactory $customerAddressRegionFactory,
        \Magento\Tax\Helper\Data $taxData,
        TaxCalculation $taxCalculation,
        RateProviderInterface $rateProvider,
        ProductResolverInterface $productResolver,
        QuoteManagementInterface $quoteManagementInterface,
        Json $serializer = null
    ) {
        parent::__construct(
            $taxConfig,
            $taxCalculationService,
            $quoteDetailsDataObjectFactory,
            $quoteDetailsItemDataObjectFactory,
            $taxClassKeyDataObjectFactory,
            $customerAddressFactory,
            $customerAddressRegionFactory,
            $taxData,
            $serializer
        );

        $this->quoteManagement = $quoteManagementInterface;
        $this->productResolver = $productResolver;
        $this->rateProvider = $rateProvider;
        $this->taxCalculation = $taxCalculation;
    }

    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, Total $total)
    {
        $this->clearValues($total);

        if (!$shippingAssignment->getItems()) {
            return $this;
        }

        $rates = $this->rateProvider->getRates($quote);

        /** @var \BA\Vertex\Api\Data\RateInterface $rate */
        foreach ($rates as $rate) {
            $this->rates[$rate->getSku()] = $rate;
        }

        /** @var \Magento\Tax\Api\Data\QuoteDetailsInterface[]|array $quoteTax */
        $quoteTax = $this->getQuoteTax($quote, $shippingAssignment, $total);

        $itemsByType = $this->organizeItemTaxDetailsByType($quoteTax['tax_details'], $quoteTax['base_tax_details']);

        if (isset($itemsByType[self::ITEM_TYPE_PRODUCT])) {
            $this->processProductItems($shippingAssignment, $itemsByType[self::ITEM_TYPE_PRODUCT], $total);
        }

        if (isset($itemsByType[self::ITEM_TYPE_SHIPPING])) {
            $shippingTaxDetails = $itemsByType[self::ITEM_TYPE_SHIPPING][self::ITEM_CODE_SHIPPING][self::KEY_ITEM];
            $baseShippingTaxDetails =
                $itemsByType[self::ITEM_TYPE_SHIPPING][self::ITEM_CODE_SHIPPING][self::KEY_BASE_ITEM];

            $this->processShippingTaxInfo(
                $shippingAssignment,
                $total,
                $shippingTaxDetails,
                $baseShippingTaxDetails
            );

            //Process taxable items that are not product or shipping
            $this->processExtraTaxables($total, $itemsByType);

            //Save applied taxes for each item and the quote in aggregation
            $this->processAppliedTaxes($total, $shippingAssignment, $itemsByType);
        }

        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return \Magento\Tax\Api\Data\QuoteDetailsInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuoteTax(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        $baseTaxDetailsInterface = $this->getQuoteTaxDetailsInterface($shippingAssignment, $total, true);
        $taxDetailsInterface = $this->getQuoteTaxDetailsInterface($shippingAssignment, $total, false);

        $baseTaxDetails = $this->getQuoteTaxDetailsOverride($quote, $baseTaxDetailsInterface, true);
        $taxDetails = $this->getQuoteTaxDetailsOverride($quote, $taxDetailsInterface, false);

        return [
            'base_tax_details' => $baseTaxDetails,
            'tax_details' => $taxDetails
        ];
    }

    /**
     *
     * @param mixed $shippingAssignment
     * @param mixed $total
     * @param mixed $useBaseCurrency
     * @return \Magento\Tax\Api\Data\QuoteDetailsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getQuoteTaxDetailsInterface($shippingAssignment, $total, $useBaseCurrency)
    {
        $address = $shippingAssignment->getShipping()->getAddress();
        //Setup taxable items
        $priceIncludesTax = $this->_config->priceIncludesTax($address->getQuote()->getStore());
        $itemDataObjects = $this->mapItems($shippingAssignment, $priceIncludesTax, $useBaseCurrency);

        //Add shipping
        $shippingDataObject = $this->getShippingDataObject($shippingAssignment, $total, $useBaseCurrency);
        if ($shippingDataObject != null) {
            $shippingDataObject = $this->extendShippingItem($shippingDataObject);
            $itemDataObjects[] = $shippingDataObject;
        }

        //process extra taxable items associated only with quote
        $quoteExtraTaxables = $this->mapQuoteExtraTaxables(
            $this->quoteDetailsItemDataObjectFactory,
            $address,
            $useBaseCurrency
        );

        if (!empty($quoteExtraTaxables)) {
            $itemDataObjects = array_merge($itemDataObjects, $quoteExtraTaxables);
        }

        //Preparation for calling taxCalculationService
        $quoteDetails = $this->prepareQuoteDetails($shippingAssignment, $itemDataObjects);

        return $quoteDetails;
    }

    public function getQuoteTaxDetailsOverride(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Tax\Api\Data\QuoteDetailsInterface $taxDetails
    ) {
        $taxDetails = $this->taxCalculation->calculateTaxDetails($quote, $taxDetails);
        
        return $taxDetails;
    }

    public function mapItem(
        \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $itemDataObjectFactory,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $priceIncludesTax,
        $useBaseCurrency,
        $parentCode = null
    ) {
        $itemDataObject = parent::mapItem(
            $itemDataObjectFactory,
            $item,
            $priceIncludesTax,
            true,
            $parentCode
        );

        /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $basysProduct */
        $basysProduct = $this->productResolver->get($this->quoteManagement->getProduct($item));
        
        $basysPrice = $basysProduct->getPrice($itemDataObject->getQuantity());
        $itemDataObject->setUnitPrice($basysPrice);

        /**
         * @var \Magento\Tax\Api\Data\QuoteDetailsItemExtensionInterface $extensionAttributes
         */
        $extensionAttributes = $itemDataObject->getExtensionAttributes()
            ? $itemDataObject->getExtensionAttributes()
            : $this->extensionFactory->create();

        /** @var \BA\Vertex\Api\Data\RateInterface $rate */
        if (isset($this->rates[$item->getProduct()->getSku()])) {
            $rate = $this->rates[$item->getProduct()->getSku()];
            
            $taxableAmount = round($itemDataObject->getUnitPrice() * $itemDataObject->getQuantity(), 4, PHP_ROUND_HALF_EVEN);
            $taxCollectable = round($taxableAmount * ($rate->getRate() / 100), 4, PHP_ROUND_HALF_EVEN);

            $extensionAttributes->setTaxCollectable($taxCollectable);
            $extensionAttributes->setTaxRate($rate->getRate());

            $jurisdictions = [
                'default' => [
                    'id' => 'vat',
                    'label' => 'VAT',
                    'rate' => $rate->getRate(),
                    'amount' => $taxCollectable
                ]
            ];

            $extensionAttributes->setJurisdictionTaxRates($jurisdictions);
        }

        $itemDataObject->setExtensionAttributes($extensionAttributes);

        return $itemDataObject;
    }

    /**
     * @param \Magento\Tax\Api\Data\QuoteDetailsItemInterface $shippingDataObject
     * @return \Magento\Tax\Api\Data\QuoteDetailsItemInterface
     */
    protected function extendShippingItem(
        \Magento\Tax\Api\Data\QuoteDetailsItemInterface $shippingDataObject
    ) {
        /** @var \Magento\Tax\Api\Data\QuoteDetailsItemExtensionInterface $extensionAttributes */
        $extensionAttributes = $shippingDataObject->getExtensionAttributes()
            ? $shippingDataObject->getExtensionAttributes()
            : $this->extensionFactory->create();

        $taxCollectable = round($shippingDataObject->getUnitPrice() * 0.20, 2);

        $extensionAttributes->setTaxCollectable($taxCollectable);
        $extensionAttributes->setTaxRate(20);

        $extensionAttributes->setJurisdictionTaxRates([
            'shipping' => [
                'id' => 'shipping',
                'label' => 'Shipping',
                'rate' => 20,
                'amount' => $taxCollectable
            ]
        ]);

        $shippingDataObject->setExtensionAttributes($extensionAttributes);

        return $shippingDataObject;
    }
}
