<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Request;

use Adflex\Payments\Gateway\Http\Client\AdflexClient;
use Adflex\Payments\Model\Jwt\Token;
use Magento\Checkout\Model\Session;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Adflex\Payments\Helper\Data;

/**
 * Class SessionDataBuilder
 *
 * @package Adflex\Payments\Gateway\Request
 */
class SessionDataBuilder implements BuilderInterface
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
     * @var \Adflex\Payments\Model\Jwt\Token
     */
    protected $_token;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customer;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $_cartRepository;
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
     * @param \Adflex\Payments\Model\Jwt\Token $jwt
     * @param \Magento\Customer\Model\Session $customer
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Adflex\Payments\Helper\Data $data
     */
    public function __construct(
        AdflexClient $adflexClient,
        Session $session,
        ScopeConfigInterface $scopeConfig,
        Token $jwt,
        CustomerSession $customer,
        CartRepositoryInterface $cartRepository,
        StoreManagerInterface $storeManager,
        Data $data
    ) {
        $this->_client = $adflexClient;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
        $this->_token = $jwt;
        $this->_customer = $customer;
        $this->_cartRepository = $cartRepository;
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
        // Rest of data to build session request.
        $quote = $this->_checkoutSession->getQuote();
        // Gets store id.
        $storeId = $this->_storeManager->getStore()->getId();
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode', ScopeInterface::SCOPE_STORE, $storeId);
        // Need to show/hide the save card option based on whether or not the customer is logged in.
        $loggedIn = $this->_customer->isLoggedIn();
        // Session endpoint.
        $endpointUrl = $mode . 'v2/ahpp/session';
        // Reset all data when rebuilding session.
        $adflexData = $this->_checkoutSession->getAdflexData();
        // Reserve order id at this time and save for later.
        if (is_null($quote->getReservedOrderId())) {
            $quote->reserveOrderId();
            $this->_cartRepository->save($quote);
        }
        // Gets appropriate currency code and grand total.
        list($grandTotal, $currencyCode) = array_values($this->_helper->getCurrencyGrandTotal($quote, $storeId));
        $adflexData['invoiceNumber'] = $quote->getReservedOrderId();
        $this->_checkoutSession->setAdflexData($adflexData);

        // Create request elements.
        return [
            'amount' => [
                'currency' => $currencyCode,
                'value' => $grandTotal
            ],
            'uri' => $endpointUrl,
            'reference' => $adflexData['invoiceNumber'],
            'templateId' => 0,
            'transactionGUID' => $this->_token->generateGuid(),
            'description' => __('Secure Payment'),
            'ttlSeconds' => 9999,
            'endPointUrl' => $endpointUrl,
            'avsCheck' => 'Default',
            'cvcCheck' => 'Default',
            'tdsCheck' => 'Default',
            'tokenLifetime' => [
                'saveCardOption' => (!$loggedIn) ? 'Hide' : 'ShowUnticked',
                'ttlDays' => -1
            ]
        ];
    }

}
