<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Response;

use Adflex\Payments\Logger\Logger\Logger;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class SessionHandler
 *
 * @package Adflex\Payments\Gateway\Response
 */
class SessionHandler implements HandlerInterface
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $_json;
    /**
     * @var \Adflex\Payments\Logger\Logger\Logger
     */
    protected $_logger;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * SessionHandler constructor.
     *
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Adflex\Payments\Logger\Logger\Logger $logger
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Json $json,
        Logger $logger,
        Session $session,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_json = $json;
        $this->_logger = $logger;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param array $handlingSubject
     * @param array $response
     * @return array|string[]|void
     * Handles session response from Adflex.
     */
    public function handle(array $handlingSubject, array $response)
    {
        // Logger
        $loggerEnabled = $this->_scopeConfig->getValue('payment/adflex/debug', ScopeInterface::SCOPE_STORE);
        // Endpoint URL mode (production/test).
        $mode = $this->_scopeConfig->getValue('payment/adflex/mode', ScopeInterface::SCOPE_STORE);
        // Actual Mode
        $actualMode = (stristr($mode, 'test')) ? 'test' : 'production';
        // If its a Magento side response (already stored session), then let's jump out.
        if (!isset($response[0])) {
            return;
        }
        // Unserialise JSON response body.
        $data = $this->_json->unserialize($response[0]);
        // Get response object
        $responseObject = $data['responseObject'];
        if ($data['statusMessage'] == 'OK') {
            // Add data to session so we can reuse it in the next step.
            $adflexData = [
                    'session_id' => $responseObject['sessionID'],
                    'transaction_guid' => $responseObject['transactionGUID'],
                    'time_to_live' => $responseObject['ttlSeconds'],
                    'sub_code' => $data['subCode'],
                    'request_made_at' => time(),
                    'mode' => $actualMode
                ];
            if ($loggerEnabled) {
                $this->_logger->addInfo('Success Response: ' . $response[0]);
            }
        } elseif (stristr($data['statusMessage'], 'Duplicate txReference')) {
            $adflexData = [
                'error_message' => 'A session has already been created for this transaction, subsequently,
                 a fatal error occurred, please empty your basket or wait until ' . date('H:i', strtotime(
                    time() + $responseObject['ttlSeconds']
                ))
            ];
            $this->_logger->addInfo('A transaction session was already created for this quote.');
        } else {
            // Handles any failure events.
            $adflexData = [
                'error_message' => $data['subCode'],
                'status_message' => $data['statusMessage']
            ];
            if ($loggerEnabled) {
                $this->_logger->addInfo('Failure Response: ' . $response[0]);
            }
        }

        // Write to the session with what we need.
        $this->_checkoutSession->setAdflexData($adflexData);
    }
}
