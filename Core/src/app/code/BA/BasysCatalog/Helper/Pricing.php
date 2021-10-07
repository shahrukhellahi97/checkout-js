<?php
namespace BA\BasysCatalog\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Psr\Log\LoggerInterface;
use BA\BasysCatalog\Api\ProductResolverInterface;
use BA\BasysCatalog\Model\ProductResolver;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Pricing extends AbstractHelper
{
    /**
     * @var \BA\BasysCatalog\Api\ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ProductResolver $productResolver,
        LoggerInterface $logger
    ) {

        parent::__construct($context);

        $this->productResolver = $productResolver;
        $this->logger = $logger;
    }

    /**
     * Get the custom price/tier prices
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param int $itemQty
     * @return float
     */
    public function getPrice(ProductInterface $product, $itemQty)
    {
        try {
            $prices = $this->productResolver->get($product)->getPrices();
            $priceVal = 0;

            foreach ($prices as $price) {
                /* assuming type id proportional to break/price qty  and get the prices on desc order */
                if ($itemQty >= $price->getBreak()) {
                    $priceVal = $price->getPrice();

                    break;
                }
            }
            return $priceVal;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
