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
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Payment\Gateway\Command\CommandManagerPoolInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class SessionDataBuilder
 *
 * @package Adflex\Payments\Gateway\Request
 */
class VaultSettleDataBuilder implements BuilderInterface
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
     * @var \Magento\Framework\Serialize\Serializer\Json
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
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Magento\Payment\Gateway\Command\CommandManagerPoolInterface $commandManagerPool
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Adflex\Payments\Helper\Data $data
     */
    public function __construct(
        AdflexClient $adflexClient,
        Session $session,
        ScopeConfigInterface $scopeConfig,
        Json $json,
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
        // Store id.
        $storeId = $this->_storeManager->getStore()->getId();
        // Payment data.
        $payment = $buildSubject['payment']->getPayment();
        // Get total + currency code.
        list($amount) = array_values($this->_helper->getCurrencyGrandTotal(
            $buildSubject['payment']->getPayment()->getOrder(),
            $storeId
        ));
        // If a pure capture command from checkout, we need to execute the authorise command first.
        if (is_null($payment->getAdditionalData())) {
            $this->_commandManager->get('adflex')->executeByCode(
                'vault_authorize',
                $payment,
                ['amount' => $amount]
            );
        }
        // Gets additional data from payment method.
        $additionalData = $this->_json->unserialize($payment->getAdditionalData());
        // Transaction ID used to settle.
        $ccTransId = $additionalData['transaction_guid'];
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode', ScopeInterface::SCOPE_STORE);
        // Session endpoint.
        $endpointUrl = $mode . 'v2/transactions/settle';

        return [
            'transactionGUID' => $ccTransId,
            'extended' => [
                'amount' => $amount
            ],
            'endPointUrl' => $endpointUrl
        ];
    }
}
