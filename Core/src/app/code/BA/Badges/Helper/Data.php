<?php
namespace BA\Badges\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data
{
    const NEW_LABEL = 'setting/new_product/label';
    const NEW_START_COLOR = 'setting/new_product/start_color';
    const NEW_END_COLOR = 'setting/new_product/end_color';
    const SALE_LABEL = 'setting/on_sale/label';
    const SALE_START_COLOR = 'setting/on_sale/start_color';
    const SALE_END_COLOR = 'setting/on_sale/end_color';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function newLabel()
    {
        return $this->scopeConfig->getValue(self::NEW_LABEL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function newStartColor()
    {
        return $this->scopeConfig->getValue(self::NEW_START_COLOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function newEndColor()
    {
        return $this->scopeConfig->getValue(self::NEW_END_COLOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function saleLabel()
    {
        return $this->scopeConfig->getValue(self::SALE_LABEL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function saleStartColor()
    {
        return $this->scopeConfig->getValue(self::SALE_START_COLOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function saleEndColor()
    {
        return $this->scopeConfig->getValue(self::SALE_END_COLOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}