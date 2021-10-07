<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 *
 * @package Adflex\Payments\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     * Returns mode the payment extension is in.
     */
    public function getActualMode()
    {
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode');
        // Actual Mode
        return (stristr($mode, 'test')) ? 'test' : 'production';
    }

    /**
     * @param $object
     * @param $storeId
     * @return array
     * Gets currency and grand total appropriately from quote/order object.
     */
    public function getCurrencyGrandTotal($object, $storeId)
    {
        $grandTotal = 0;
        $currencyCode = '';
        // Currency selector
        $currency = $this->_scopeConfig->getValue('payment/adflex/base_currency', ScopeInterface::SCOPE_STORE, $storeId);
        // Select appropriate currency and grand total based on store configuration.
        switch ($currency) {
            case 'base':
                $grandTotal = $this->getAsPence($object->getBaseGrandTotal());
                $currencyCode = $object->getBaseCurrencyCode();
                break;
            case 'selector':
            case 'store':
                $grandTotal = $this->getAsPence($object->getGrandTotal());
                $currencyCode = $object->getOrderCurrencyCode();
                if (is_null($currencyCode)) {
                    $currencyCode = $object->getQuoteCurrencyCode();
                }
                break;
        }

        return [$grandTotal, $currencyCode];
    }

    /**
     * @param $grandTotal
     * @return string|string[]
     * Converts price into pence.
     */
    private function getAsPence($grandTotal)
    {
        return str_replace('.', '', (string)number_format($grandTotal, 2));
    }
}
