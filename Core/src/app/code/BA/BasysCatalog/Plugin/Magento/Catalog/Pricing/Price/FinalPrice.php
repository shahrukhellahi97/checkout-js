<?php
namespace BA\BasysCatalog\Plugin\Magento\Catalog\Pricing\Price;

use BA\BasysCatalog\Api\ProductResolverInterface;
use Magento\Store\Model\StoreManagerInterface;

class FinalPrice
{
    /**
     * @var \BA\BasysProduct\Api\ProductResolverInterface
     */
    protected $productResolver;

    protected $storeManager;

    public function __construct(
        ProductResolverInterface $productResolver,
        StoreManagerInterface $storeManager
    ) {
        $this->productResolver = $productResolver;
        $this->storeManager = $storeManager;
    }

    public function beforeGetValue(
        \Magento\Catalog\Pricing\Price\FinalPrice $subject
    ) {
       
        try {
            $product = $this->productResolver->get($subject->getProduct());
            $tierPrices = $product->getPrices();
            $product = $subject->getProduct();
            foreach ($tierPrices as $tierPrice) {
                $finalTierPrice[] = [
                    'cust_group'    => '0',
                    'price_qty' => $tierPrice->getBreak(),
                    'price'   => $tierPrice->getPrice(),
                    'website_id' => $this->getCurrentWebsiteId()];
            }
            $product->setData('tier_price', $finalTierPrice);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function getCurrentWebsiteId()
    {
        return $this->storeManager->getStore()->getWebsiteId();
    }
}
