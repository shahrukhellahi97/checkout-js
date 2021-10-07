<?php
namespace BA\Vertex\Plugin;

use BA\Vertex\Helper\Data as VertexHelper;
use Magento\Framework\Pricing\Amount\AmountFactory;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Tax\Helper\Data as TaxHelper;

class AdjustmentCalculator
{
    /**
     * @var \Magento\Framework\Pricing\Amount\AmountFactory
     */
    protected $amountFactory;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    protected $taxHelper;

    protected $vertexHelper;

    public function __construct(
        AmountFactory $amountFactory,
        TaxHelper $taxHelper,
        VertexHelper $vertexHelper
    ) {
        $this->amountFactory = $amountFactory;
        $this->taxHelper = $taxHelper;
        $this->vertexHelper = $vertexHelper;
    }

    public function aroundGetAmount(
        \Magento\Framework\Pricing\Adjustment\Calculator $subject,
        callable $proceed,
        $amount,
        SaleableInterface $saleableItem,
        $exclude = null,
        $context = []
    ) {
        // This isn't very clever
        if ($this->shouldApplyAdjustment()) {
            $rate = $this->vertexHelper->getDefaultRate();
            $taxCollectable = round($amount * ($rate / 100), 4, PHP_ROUND_HALF_EVEN);

            $returnValue = $amount + $taxCollectable;
            $adjustments = ['tax' => $taxCollectable];

            return $this->amountFactory->create($returnValue, $adjustments);
        }

        return $proceed($amount, $saleableItem, $exclude, $context);
    }

    private function shouldApplyAdjustment()
    {
        return $this->taxHelper->displayPriceIncludingTax() || $this->taxHelper->displayBothPrices();
    }
}
