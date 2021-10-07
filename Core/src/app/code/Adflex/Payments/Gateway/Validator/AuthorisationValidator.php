<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Validator;

use Adflex\Payments\Logger\Logger\Logger;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class AuthorisationValidator
 *
 * @package Adflex\Payments\Gateway\Validator
 */
class AuthorisationValidator extends AbstractValidator
{
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_json;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Adflex\Payments\Logger\Logger\Logger
     */
    protected $_logger;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * AuthorisationValidator constructor.
     *
     * @param \Magento\Payment\Gateway\Validator\ResultInterfaceFactory $resultFactory
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Adflex\Payments\Logger\Logger\Logger $logger
     * @param \Magento\Checkout\Model\Session $session
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        SerializerInterface $serializer,
        ScopeConfigInterface $scopeConfig,
        Logger $logger,
        Session $session
    ) {
        parent::__construct($resultFactory);
        $this->_json = $serializer;
        $this->_checkoutSession = $session;
        $this->_logger = $logger;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Performs validation of result code
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['response']) || !isset($validationSubject['response'][0])
            || !is_array($validationSubject['response'])) {
            throw new \InvalidArgumentException('Response does not exist');
        }

        $validationSubject['response'] = $this->_json->unserialize($validationSubject['response'][0]);
        $response = $validationSubject['response'];

        $loggerEnabled = $this->_scopeConfig->getValue('payment/adflex/debug', ScopeInterface::SCOPE_STORE);
        if ($this->isSuccessfulTransaction($response)) {
            // Log data for debugging.
            if ($loggerEnabled) {
                $this->_logger->addInfo(
                    'Successful response: ' . $response['responseObject']['statusCode'] .
                    ', code: ' . $response['subCode'] . ', message: ' . $response['statusMessage']
                );
            }
            return $this->createResult(
                true,
                []
            );
        } else {
            // Unset data on failure so it can be rebuilt again.
            $this->_checkoutSession->unsAdflexData();
            // Log data for debugging.
            if ($loggerEnabled) {
                $this->_logger->addCritical(
                    'Error on response from Adflex, JSON: '
                    . $this->_json->serialize($response)
                );
            }

            return $this->createResult(
                false,
                [__('Gateway rejected the transaction.')]
            );
        }
    }

    /**
     * @param array $response
     * @return bool
     * Returns whether or not a successful authorisation.
     */
    private function isSuccessfulTransaction(array $response)
    {
        $statusCode = $response['responseObject']['statusCode'];
        switch ($statusCode) {
            case 'Authorised':
            case 'MerchantAccepted':
            case 'Settled':
            case 'MerchantPending':
            case 'AuthorisedOnly':
            /** @var bool $response */
                $response = true;
                break;
            default:
                $response = false;
                break;
        }
        return $response;
    }
}
