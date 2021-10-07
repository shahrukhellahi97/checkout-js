<?php
namespace BA\BasysCatalog\Pricing\Price;

use BA\Basys\Exception\BasysException;
use BA\BasysCatalog\Api\ProductResolverInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Pricing\Price\FinalPrice as PriceFinalPrice;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Amount\AmountFactory;
use Magento\Framework\Pricing\SaleableInterface;

class FinalPrice extends PriceFinalPrice
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

    protected $amount;
    
    protected $value;

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
        $this->productFactory = $productFactory;
        $this->amountFactory = $amountFactory;
    }

    public function getValue()
    {
        if (!$this->value) {
            try {
                $product = $this->productResolver->get($this->getProductModel());
                $this->value = $product->getPrice();
            } catch (BasysException $e) {
                $this->value = parent::getValue();
            }
        }

        return $this->value;
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
