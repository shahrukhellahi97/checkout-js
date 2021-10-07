<?php
namespace BA\BasysCatalog\Pricing\Price;

use BA\Basys\Exception\BasysException;
use BA\BasysCatalog\Api\ProductResolverInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Pricing\Price\RegularPrice as PriceRegularPrice;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\SaleableInterface;

class RegularPrice extends PriceRegularPrice
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
        ProductInterfaceFactory $productFactory
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);

        $this->productResolver = $productResolver;
        $this->productFactory = $productFactory;
    }

    public function getValue()
    {
        try {
            $product = $this->productResolver->get($this->getProductModel());
            
            return $product->getPrice();
        } catch (BasysException $e) {
            return parent::getValue();
        }
        
        // try {

        // //     $product = $this->productResolver->get($this->getProductModel());
        // //     // $finalTierPrice[0] = [
        // //     //     'cust_group'    => '0',
        // //     //     'price_qty' => 3,
        // //     //     'price'   => 2,
        // //     //     'website_id' => '0'];
        // //     // $finalTierPrice[1] = [
        // //     //     'cust_group'    => '0',
        // //     //     'price_qty' => 4,
        // //     //     'price'   => 3,
        // //     //     'website_id' => '0'];
        // //     // $product->setData('tier_price', $finalTierPrice);
        // //    // $p = $product->getPrices();
        // //     return $product->getPrice();
           
        // } catch (BasysException $e) {
        //     return parent::getValue();
        // }
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
