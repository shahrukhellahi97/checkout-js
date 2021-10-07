<?php
namespace BA\BasysCatalog\Pricing\Price;

use BA\BasysCatalog\Api\ProductResolverInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Amount\AmountFactory;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\SaleableInterface;

abstract class BasysPrice extends AbstractPrice
{
    /**
     * @var \BA\BasysProduct\Api\ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var \Magento\Catalog\Api\Data\ProductInterfaceFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Framework\Pricing\Amount\AmountFactory
     */
    protected $amountFactory;

    public function __construct(
        SaleableInterface $saleableItem,
        $quantity,
        CalculatorInterface $calculator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        ProductResolverInterface $productResolver,
        ProductInterfaceFactory $productFactory,
        AmountFactory $amountFactory
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);

        $this->productResolver = $productResolver;
        $this->amountFactory = $amountFactory;
        $this->productFactory = $productFactory;
    }

    /**
     * Get product interface from SaleableInterface
     * 
     * @return \Magento\Catalog\Api\Data\ProductInterface 
     */
    public function getProductModel()
    {
        $saleableProduct = $this->getProduct();

        if ($saleableProduct instanceof ProductInterface) {
            return $saleableProduct;
        }

        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        $product = $this->productFactory->create();
        $product->setId($this->getProduct()->getId());

        return $product;
    }
}