<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Request;

use Adflex\Payments\Gateway\Http\Client\AdflexClient;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class SessionDataBuilder
 *
 * @package Adflex\Payments\Gateway\Request
 */
class TokenInfoDataBuilder implements BuilderInterface
{
    /**
     * @var \Adflex\Payments\Gateway\Http\Client\AdflexClient
     */
    private $_client;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Adflex\Payments\Logger\Logger\Logger
     */
    protected $_logger;

    /**
     * SessionDataBuilder constructor.
     *
     * @param \Adflex\Payments\Gateway\Http\Client\AdflexClient $adflexClient
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        AdflexClient $adflexClient,
        Session $session,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_client = $adflexClient;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param array $buildSubject
     * @return array
     * Builds request to get token information (so we know if we can store + card details for enhanced data.
     */
    public function build(array $buildSubject)
    {
        // Get adflex session data (card token).
        $adflexData = $this->_checkoutSession->getAdflexData();
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode', ScopeInterface::SCOPE_STORE);
        // Session endpoint.
        $endpointUrl = $mode . 'v2/tokens';

        return [
            'endPointUrl' => $endpointUrl,
            'endPointUrlToken' => $mode . 'v2/tokens/' . $adflexData['token']
        ];
    }

}
