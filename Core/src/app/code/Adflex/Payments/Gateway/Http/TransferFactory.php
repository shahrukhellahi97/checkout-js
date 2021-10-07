<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Http;

use Adflex\Payments\Gateway\Http\Client\AdflexClient;
use Adflex\Payments\Logger\Logger\Logger;
use Adflex\Payments\Model\Adminhtml\Source\Mode;
use Adflex\Payments\Model\Jwt\Token;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class TransferFactory
 *
 * @package Adflex\Payments\Gateway\Http
 */
class TransferFactory implements TransferFactoryInterface
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
     * @var \Adflex\Payments\Model\Jwt\Token
     */
    private $_jwtToken;
    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $_httpClient;
    /**
     * @var \Adflex\Payments\Model\Adminhtml\Source\Mode
     */
    protected $_environment;
    /**
     * @var \Adflex\Payments\Logger\Logger\Logger
     */
    protected $_logger;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $_json;
    /**
     * @var \Magento\Payment\Gateway\Http\TransferBuilder
     */
    protected $_transferBuilder;

    /**
     * SessionRequest constructor.
     *
     * @param \Adflex\Payments\Gateway\Http\Client\AdflexClient $adflexClient
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $session
     * @param \Adflex\Payments\Model\Jwt\Token $jwtToken
     * @param \Magento\Framework\HTTP\ZendClientFactory $_httpClient
     * @param \Magento\Payment\Gateway\Http\TransferBuilder $transferBuilder
     * @param \Adflex\Payments\Model\Adminhtml\Source\Mode $mode
     * @param \Adflex\Payments\Logger\Logger\Logger $logger
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     */
    public function __construct(
        AdflexClient $adflexClient,
        ScopeConfigInterface $scopeConfig,
        Session $session,
        Token $jwtToken,
        ZendClientFactory $_httpClient,
        TransferBuilder $transferBuilder,
        Mode $mode,
        Logger $logger,
        Json $json
    ) {
        $this->_client = $adflexClient;
        $this->_scopeConfig = $scopeConfig;
        $this->_checkoutSession = $session;
        $this->_jwtToken = $jwtToken;
        $this->_httpClient = $_httpClient;
        $this->_environment = $mode;
        $this->_logger = $logger;
        $this->_json = $json;
        $this->_transferBuilder = $transferBuilder;
    }

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request)
    {
        // Gets APG ID.
        $apgId = $this->_scopeConfig->getValue('payment/adflex/apgid', ScopeInterface::SCOPE_STORE);
        // Gets access key.
        $accessKey = $this->_scopeConfig->getValue('payment/adflex/access_key', ScopeInterface::SCOPE_STORE);
        // And secret key.
        $secretKey = $this->_scopeConfig->getValue('payment/adflex/secret_key', ScopeInterface::SCOPE_STORE);
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode', ScopeInterface::SCOPE_STORE);
        // Generates JWT token for request.
        $jwtToken = $this->_jwtToken->generateToken($request['endPointUrl'], $secretKey);
        // Unset's endpoint url as not used in actual request. We use the token URL to get card details.
        $hasTokenRequest = (isset($request['endPointUrlToken']));
        $finalUri = $hasTokenRequest ? $request['endPointUrlToken'] : $request['endPointUrl'];
        $postType = $hasTokenRequest ? 'GET' : 'POST';
        unset($request['endPointUrl']);
        if (isset($request['endPointUrlToken'])) {
            unset($request['endPointUrlToken']);
        }
        $body = $hasTokenRequest ? '' : $this->_json->serialize($request);

        // Return transfer builder instance for actual request.
        return $this->_transferBuilder
            ->setBody($body)
            ->setMethod($postType)
            ->setUri($finalUri)
            ->shouldEncode(isset($request['endPointUrlToken']))
            ->setHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $jwtToken,
                'x-request-apgID' => $apgId,
                'x-request-accessKey' => $accessKey
            ])
            ->build();
    }
}
