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
use Magento\Store\Model\StoreManagerInterface;
use Adflex\Payments\Helper\Data;

/**
 * Class SessionDataBuilder
 *
 * @package Adflex\Payments\Gateway\Request
 */
class AuthorisationDataBuilder implements BuilderInterface
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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Adflex\Payments\Helper\Data
     */
    protected $_helper;

    /**
     * SessionDataBuilder constructor.
     *
     * @param \Adflex\Payments\Gateway\Http\Client\AdflexClient $adflexClient
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Adflex\Payments\Helper\Data $data
     */
    public function __construct(
        AdflexClient $adflexClient,
        Session $session,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Data $data
    ) {
        $this->_client = $adflexClient;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_helper = $data;
    }

    /**
     * @param array $buildSubject
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * Builds request for Adflex session.
     */
    public function build(array $buildSubject)
    {
        // Get order data.
        $order = $buildSubject['payment']->getOrder();
        // Gets store id.
        $storeId = $this->_storeManager->getStore()->getId();
        // Get adflex session data (card token).
        $adflexData = $this->_checkoutSession->getAdflexData();
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode', ScopeInterface::SCOPE_STORE, $storeId);
        // Session endpoint.
        $endpointUrl = $mode . 'v2/ahpp/authorisation/token';
        // Gets grand total and currency code
        list($grandTotal, $currencyCode) = array_values($this->_helper->getCurrencyGrandTotal(
            $buildSubject['payment']->getPayment()->getOrder(),
            $storeId
        ));

        return [
            'cardDetails' => [
                'token' => $adflexData['token']
            ],
            'transactionDetails' => [
                'transactionGUID' => $adflexData['transaction_guid'],
                'processMode' => 'AuthOnly',
                'captureMode' => 'ECOMM',
                'enhancedData' => [],
                'enhancedDataType' => 'Level1',
                'originatingIP' => $order->getRemoteIp()
            ],
            'amount' => [
                'currency' => $currencyCode,
                'value' => $grandTotal
            ],
            'endPointUrl' => $endpointUrl
        ];
    }

}
