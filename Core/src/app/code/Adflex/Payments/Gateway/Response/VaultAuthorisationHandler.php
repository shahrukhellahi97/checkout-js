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
use Magento\Sales\Model\Order;

/**
 * Class SessionHandler
 *
 * @package Adflex\Payments\Gateway\Response
 */
class VaultAuthorisationHandler implements HandlerInterface
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
        $response = $this->_json->unserialize($response[0]);
        $payment = $handlingSubject['payment']->getPayment();
        $responseObject = $response['responseObject'];
        if ($response['subCode'] === 50001) {
            // Set card details
            $cardDetails = $responseObject['cardDetails'];
            $transactionDetails = $responseObject['transactionDetails'];
            // Card details we can use for admin later.
            $payment->setData('cc_type', $cardDetails['scheme']);
            $payment->setData('cc_last_4', $cardDetails['cardLast4Digits']);
            $payment->setAdditionalData(
                $this->_json->serialize([
                    '3dsecure' => $transactionDetails['threeDSResult'],
                    'transaction_guid' => $transactionDetails['transactionGUID'],
                    'avs_status' => $cardDetails['cscAvsResponse']
                ])
            );
        }
    }
}
