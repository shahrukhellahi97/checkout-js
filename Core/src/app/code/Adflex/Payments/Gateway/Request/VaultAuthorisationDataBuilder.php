<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Request;

use Adflex\Payments\Gateway\Http\Client\AdflexClient;
use Adflex\Payments\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Payment\Gateway\Command\CommandManagerPoolInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class SessionDataBuilder
 *
 * @package Adflex\Payments\Gateway\Request
 */
class VaultAuthorisationDataBuilder implements BuilderInterface
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
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_json;
    /**
     * @var \Magento\Payment\Gateway\Command\CommandManagerPoolInterface
     */
    protected $_commandManager;
    /**
     * @var \Adflex\Payments\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * SessionDataBuilder constructor.
     *
     * @param \Adflex\Payments\Gateway\Http\Client\AdflexClient $adflexClient
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Serialize\SerializerInterface $json
     * @param \Magento\Payment\Gateway\Command\CommandManagerPoolInterface $commandManagerPool
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Adflex\Payments\Helper\Data $data
     */
    public function __construct(
        AdflexClient $adflexClient,
        Session $session,
        ScopeConfigInterface $scopeConfig,
        SerializerInterface $json,
        CommandManagerPoolInterface $commandManagerPool,
        StoreManagerInterface $storeManager,
        Data $data
    ) {
        $this->_client = $adflexClient;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
        $this->_json = $json;
        $this->_commandManager = $commandManagerPool;
        $this->_helper = $data;
        $this->_storeManager = $storeManager;
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
        // Payment.
        $payment = $buildSubject['payment']->getPayment();
        // Store id.
        $storeId = $this->_storeManager->getStore()->getId();
        // Token
        $tokenData = $payment->getExtensionAttributes()->getVaultPaymentToken();
        // Token Details
        $tokenDetails = $this->_json->unserialize($tokenData->getData('details'));
        // Get adflex session data (card token).
        $adflexData = $this->_checkoutSession->getAdflexData();
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode', ScopeInterface::SCOPE_STORE);
        // Session endpoint.
        $endpointUrl = $mode . 'v2/transactions/authorisation/token';
        // Get total + currency code.
        list($amount, $currencyCode) = array_values($this->_helper->getCurrencyGrandTotal(
            $buildSubject['payment']->getPayment()->getOrder(),
            $storeId
        ));

        // If the transaction guid is not set, we know a session has not been created.
        if (!isset($adflexData['transaction_guid'])) {
            if (is_null($payment->getAdditionalData())) {
                // Create a session.
                $this->_commandManager->get('adflex')->executeByCode(
                    'initialize',
                    $payment,
                    ['amount' => $amount]
                );
                // Update from session data.
                $adflexData = $this->_checkoutSession->getAdflexData();
            }
        }

        return [
            'amount' => [
                'currency' => $currencyCode,
                'value' => $amount
            ],
            'cardDetails' => [
                'token' => $tokenData->getData('gateway_token'),
                'cardLast4Digits' => $tokenDetails['maskedCC'],
                'csc' => $adflexData['cvc']
            ],
            'transactionDetails' => [
                'transactionGUID' => $adflexData['transaction_guid'],
                'reference' => $order->getOrderIncrementId(),
                'type' => 'Sale',
                'processMode' => 'AuthOnly',
                'captureMode' => 'ECOMM',
                'enhancedData' => [],
                'enhancedDataType' => (isset($tokenDetails['enhancedDataType'])) ? $tokenDetails['enhancedDataType'] : 'Level1',
                'originatingIP' => $order->getRemoteIp()
            ],
            'endPointUrl' => $endpointUrl
        ];
    }

}
