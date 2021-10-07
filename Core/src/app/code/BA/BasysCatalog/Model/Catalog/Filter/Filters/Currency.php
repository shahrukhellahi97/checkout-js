<?php
namespace BA\BasysCatalog\Model\Catalog\Filter\Filters;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\Catalog\Filter\FilterInterface;
use Magento\Store\Model\StoreManagerInterface;

class Currency implements FilterInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    public function test(CatalogInterface $catalog): bool
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();

        $displayCurrency = $store->getCurrentCurrencyCode();
        $catalogCurrency = $this->parseCurrencyCode($catalog);

        return $displayCurrency === $catalogCurrency;
    }
    
    private function parseCurrencyCode(CatalogInterface $catalog)
    {
        $currency = trim($catalog->getCurrency());

        if (strlen($currency) === 0) {
            return 'GBP';
        }

        return $this->removeCountryEntity($currency);
    }

    private function removeCountryEntity(string $currency)
    {
        $patterns = [
            '/^GERMANY/i',
            '/^TURKEY/i',
            '/^IRELAND/i'
        ];

        return preg_replace($patterns, '', $currency);
    }
}
