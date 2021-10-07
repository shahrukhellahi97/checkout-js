<?php
namespace BA\Vertex\Model\Tax;

use BA\Vertex\Api\RateProviderInterface;
use Magento\Quote\Model\Quote;
use Magento\Tax\Api\TaxClassManagementInterface;
use Magento\Tax\Api\Data\AppliedTaxInterfaceFactory;
use Magento\Tax\Api\Data\AppliedTaxRateInterfaceFactory;
use Magento\Tax\Api\Data\QuoteDetailsItemInterface;
use Magento\Tax\Api\Data\TaxDetailsInterfaceFactory;
use Magento\Tax\Api\Data\TaxDetailsItemInterfaceFactory;
use Magento\Tax\Model\Calculation;
use Magento\Tax\Model\Calculation\CalculatorFactory;
use Magento\Tax\Model\Config;
use Magento\Tax\Model\TaxDetails\TaxDetails;
use Magento\Tax\Api\Data\QuoteDetailsInterface;
use Magento\Store\Model\StoreManagerInterface;

class TaxCalculation extends \Magento\Tax\Model\TaxCalculation
{

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Magento\Tax\Api\Data\AppliedTaxInterfaceFactory
     */
    protected $appliedTaxDataObjectFactory;

    /**
     * @var \Magento\Tax\Api\Data\AppliedTaxRateInterfaceFactory
     */
    protected $appliedTaxRateDataObjectFactory;

    /**
     * @var \Magento\Tax\Api\Data\QuoteDetailsItemInterface[]
     */
    protected $keyedQuoteDetailItems;

    public function __construct(
        Calculation $calculation,
        CalculatorFactory $calculatorFactory,
        Config $config,
        TaxDetailsInterfaceFactory $taxDetailsDataObjectFactory,
        TaxDetailsItemInterfaceFactory $taxDetailsItemDataObjectFactory,
        StoreManagerInterface $storeManager,
        TaxClassManagementInterface $taxClassManagement,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        AppliedTaxInterfaceFactory $appliedTaxDataObjectFactory,
        AppliedTaxRateInterfaceFactory $appliedTaxRateDataObjectFactory
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->appliedTaxDataObjectFactory = $appliedTaxDataObjectFactory;
        $this->appliedTaxRateDataObjectFactory = $appliedTaxRateDataObjectFactory;

        return parent::__construct(
            $calculation,
            $calculatorFactory,
            $config,
            $taxDetailsDataObjectFactory,
            $taxDetailsItemDataObjectFactory,
            $storeManager,
            $taxClassManagement,
            $dataObjectHelper
        );
    }

    public function calculateTaxDetails(Quote $quote, QuoteDetailsInterface $quoteDetails)
    {
        $taxDetailsData = [
            TaxDetails::KEY_SUBTOTAL => 0.0,
            TaxDetails::KEY_TAX_AMOUNT => 0.0,
            TaxDetails::KEY_DISCOUNT_TAX_COMPENSATION_AMOUNT => 0.0,
            TaxDetails::KEY_APPLIED_TAXES => [],
            TaxDetails::KEY_ITEMS => [],
        ];

        $items = $quoteDetails->getItems();

        $keyedItems = [];
        $parentToChildren = [];

        foreach ($items as $item) {
            if ($item->getParentCode() === null) {
                $keyedItems[$item->getCode()] = $item;
            } else {
                $parentToChildren[$item->getParentCode()][] = $item;
            }
        }

        $this->keyedQuoteDetailItems = $keyedItems;

        $processedItems = [];
        /** @var QuoteDetailsItemInterface $item */
        foreach ($keyedItems as $item) {
            if (isset($parentToChildren[$item->getCode()])) {
                $processedChildren = [];
                /** @var \Magento\Tax\Api\Data\QuoteDetailsItemInterface $child */
                foreach ($parentToChildren[$item->getCode()] as $child) {
                    $processedItem = $this->processItemDetails($child);
                    $taxDetailsData = $this->aggregateItemData($taxDetailsData, $processedItem);
                    $processedItems[$processedItem->getCode()] = $processedItem;
                    $processedChildren[] = $processedItem;
                }
                $processedItem = $this->calculateParent($processedChildren, $item->getQuantity());
                $processedItem->setCode($item->getCode());
                $processedItem->setType($item->getType());
            } else {
                $processedItem = $this->processItemDetails($item);
                $taxDetailsData = $this->aggregateItemData($taxDetailsData, $processedItem);
            }
            $processedItems[$processedItem->getCode()] = $processedItem;
        }

        $taxDetailsDataObject = $this->taxDetailsDataObjectFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $taxDetailsDataObject,
            $taxDetailsData,
            \Magento\Tax\Api\Data\TaxDetailsInterface::class
        );

        $taxDetailsDataObject->setItems($processedItems);
        return $taxDetailsDataObject;
    }

    /**
     * @param string $code
     * @return \BA\Vertex\Api\Data\RateInterface|null
     */
    protected function getRateBySku($code)
    {
        return isset($this->rates[$code]) ? $this->rates[$code] : null;
    }

    protected function processItemDetails(QuoteDetailsItemInterface $item)
    {
        $price = $item->getUnitPrice();
        $quantity = $this->getTotalQuantity($item);

        $extensionAttributes = $item->getExtensionAttributes();
        $taxCollectable = $extensionAttributes ? $extensionAttributes->getTaxCollectable() : 0;
        $taxPercent = $extensionAttributes ? $extensionAttributes->getTaxRate() : 0;

        $rowTotal = $price * $quantity;
        $rowTotalInclTax = $rowTotal + $taxCollectable;

        $priceInclTax = $rowTotalInclTax / $quantity;
        $discountTaxCompensationAmount = 0;

        $appliedTaxes = $this->getAppliedTaxes($item);

        return $this->taxDetailsItemDataObjectFactory->create()
             ->setCode($item->getCode())
             ->setType($item->getType())
             ->setRowTax($taxCollectable)
             ->setPrice($price)
             ->setPriceInclTax($priceInclTax)
             ->setRowTotal($rowTotal)
             ->setRowTotalInclTax($rowTotalInclTax)
             ->setDiscountTaxCompensationAmount($discountTaxCompensationAmount)
             ->setAssociatedItemCode($item->getAssociatedItemCode())
             ->setTaxPercent($taxPercent)
             ->setAppliedTaxes($appliedTaxes);
    }

    protected function getAppliedTaxes(QuoteDetailsItemInterface $item) {
        $extensionAttributes = $item->getExtensionAttributes();
        $jurisdictionTaxRates = $extensionAttributes ? $extensionAttributes->getJurisdictionTaxRates() : [];
        $appliedTaxes = [];

        if (empty($jurisdictionTaxRates)) {
            return $appliedTaxes;
        }

        foreach ($jurisdictionTaxRates as $jurisdiction => $jurisdictionTax) {
            if ($jurisdictionTax['rate'] == 0) {
                continue;
            }

            $rateDataObject = $this->appliedTaxRateDataObjectFactory->create()
                ->setPercent($jurisdictionTax['rate'])
                ->setCode($jurisdictionTax['id'])
                ->setTitle($jurisdictionTax['label']);

            $appliedTaxDataObject = $this->appliedTaxDataObjectFactory->create();
            $appliedTaxDataObject->setAmount($jurisdictionTax['amount']);
            $appliedTaxDataObject->setPercent($jurisdictionTax['rate']);
            $appliedTaxDataObject->setTaxRateKey($jurisdictionTax['id']);
            $appliedTaxDataObject->setRates([$rateDataObject]);

            $appliedTaxes[$appliedTaxDataObject->getTaxRateKey()] = $appliedTaxDataObject;
        }

        return $appliedTaxes;
    }
}