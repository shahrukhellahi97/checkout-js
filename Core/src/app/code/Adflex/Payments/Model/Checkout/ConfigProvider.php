<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Model\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Config\Model\Config\Backend\Image\Logo;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Locale\Resolver;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\UrlInterface;
use Adflex\Payments\Ui\ConfigProvider as PaymentConfigProvider;

/**
 * Class ModePlugin
 *
 * @package Adflex\Payments\Plugins
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $_locale;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * ModePlugin constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Locale\Resolver $locale
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Resolver $locale,
        UrlInterface $url
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_locale = $locale;
        $this->_urlInterface = $url;
    }

    /**
     * @return array|mixed
     * Adds additional configuration values for Adflex JS widget instantiation.
     */
    public function getConfig()
    {
        // Environment
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode');
        // Logo
        $storeLogoPath = Logo::UPLOAD_DIR . '/' . $this->_scopeConfig->getValue(
            'design/header/logo_src',
            ScopeInterface::SCOPE_STORE
        );
        $logoUrl = $this->_urlInterface->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . $storeLogoPath;

        // Checkout config JSON output.
        return [
            'adflex' => [
                'environment' => (stristr($mode, 'test')) ? 'test' : 'production',
                'display_type' => $this->_scopeConfig->getValue('payment/adflex/type'),
                'locale' => str_replace('_', '-', $this->_locale->getLocale()),
                'store_logo' => $logoUrl
            ],
            'payment' => [
                PaymentConfigProvider::CODE => [
                    'ccVaultCode' => PaymentConfigProvider::CC_VAULT_CODE
                ]
            ]
        ];
    }
}
