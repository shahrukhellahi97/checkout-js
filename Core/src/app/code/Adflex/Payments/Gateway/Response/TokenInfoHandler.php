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
use Magento\Payment\Gateway\Command\CommandManagerPoolInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;

/**
 * Class SessionHandler
 *
 * @package Adflex\Payments\Gateway\Response
 */
class TokenInfoHandler implements HandlerInterface
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
     * @var \Magento\Payment\Gateway\Command\CommandManagerPoolInterface
     */
    protected $_commandManager;
    /**
     * @var \Magento\Payment\Gateway\Helper\SubjectReader
     */
    protected $_subjectReader;

    /**
     * SessionHandler constructor.
     *
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Adflex\Payments\Logger\Logger\Logger $logger
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Gateway\Command\CommandManagerPoolInterface $commandManagerPool
     * @param \Magento\Payment\Gateway\Helper\SubjectReader $subjectReader
     */
    public function __construct(
        Json $json,
        Logger $logger,
        Session $session,
        ScopeConfigInterface $scopeConfig,
        CommandManagerPoolInterface $commandManagerPool,
        SubjectReader $subjectReader
    ) {
        $this->_json = $json;
        $this->_logger = $logger;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
        $this->_commandManager = $commandManagerPool;
        $this->_subjectReader = $subjectReader;
    }

    /**
     * @param array $handlingSubject
     * @param array $response
     * @return array|string[]|void
     * Handles token information data, and saves it for later use.
     */
    public function handle(array $handlingSubject, array $response)
    {
        $response = $this->_json->unserialize($response[0]);
        $responseObject = $response['responseObject'];
        $adflexData = $this->_checkoutSession->getAdflexData();
        // We need this data for Magento Vault + Enhanced data for card providers.
        $adflexData['tokenType'] = $responseObject['tokenType'];
        $adflexData['scheme'] = $responseObject['cardDetails']['scheme'];
        $adflexData['enhancedDataType'] = $responseObject['cardDetails']['enhancedDataType'];
        $adflexData['tokenExpiry'] = $responseObject['tokenExpiryDate'];
        $adflexData['cardLast4Digits'] = $responseObject['cardDetails']['cardLast4Digits'];
        $adflexData['cardExpiryMonth'] = $responseObject['cardDetails']['expiryDate']['month'];
        $adflexData['cardExpiryYear'] = $responseObject['cardDetails']['expiryDate']['year'];
        $this->_checkoutSession->setAdflexData($adflexData);
        // As this is an initialize (we need the token info) function, we have to trigger off the payment action.
        $paymentAction = (stristr($handlingSubject['paymentAction'], 'capture')) ? 'capture' : 'authorize';
        $paymentSubject = $this->_subjectReader::readPayment($handlingSubject);
        $this->_commandManager->get('adflex')->executeByCode(
            $paymentAction,
            $paymentSubject->getPayment(),
            $handlingSubject
        );
    }
}
