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

/**
 * Class SessionHandler
 *
 * @package Adflex\Payments\Gateway\Response
 */
class SettleHandler implements HandlerInterface
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
        $payment = $handlingSubject['payment']->getPayment();
        // Gets additional data from payment method.
        $settleResponse = $this->_json->unserialize($response[0]);
        $responseObject = $settleResponse['responseObject'];
        if (isset($handlingSubject['stateObject'])) {
            $handlingSubject['stateObject']->setState('processing');
            $handlingSubject['stateObject']->setStatus('processing');
        }
        $payment->setData('transaction_id', $responseObject['transactionDetails']['transactionGUID']);
        $payment->setData('cc_status', $responseObject['statusCode']);
        // Clear out session data as no longer useful, create anew if needed.
        $this->_checkoutSession->unsAdflexData();
    }
}
