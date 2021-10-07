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
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Api\Data\OrderPaymentExtensionInterface;
use Magento\Vault\Api\Data\PaymentTokenFactoryInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Api\Data\OrderPaymentExtensionInterfaceFactory;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class SessionHandler
 *
 * @package Adflex\Payments\Gateway\Response
 */
class VaultHandler implements HandlerInterface
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
     * @var \Magento\Payment\Gateway\Helper\SubjectReader
     */
    protected $_subjectReader;
    /**
     * @var \Magento\Vault\Api\Data\PaymentTokenInterface
     */
    protected $_paymentTokenInterface;
    /**
     * @var \Magento\Vault\Api\Data\PaymentTokenFactoryInterface
     */
    protected $_paymentTokenFactoryInterface;
    /**
     * @var \Magento\Sales\Api\Data\OrderPaymentExtensionInterfaceFactory
     */
    protected $_paymentExtensionFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * SessionHandler constructor.
     *
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Adflex\Payments\Logger\Logger\Logger $logger
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Gateway\Helper\SubjectReader $subjectReader
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $paymentToken
     * @param \Magento\Vault\Api\Data\PaymentTokenFactoryInterface $paymentTokenFactory
     * @param \Magento\Sales\Api\Data\OrderPaymentExtensionInterfaceFactory $paymentExtensionInterfaceFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        Json $json,
        Logger $logger,
        Session $session,
        ScopeConfigInterface $scopeConfig,
        SubjectReader $subjectReader,
        PaymentTokenInterface $paymentToken,
        PaymentTokenFactoryInterface $paymentTokenFactory,
        OrderPaymentExtensionInterfaceFactory $paymentExtensionInterfaceFactory,
        CustomerSession $customerSession
    ) {
        $this->_json = $json;
        $this->_logger = $logger;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
        $this->_subjectReader = $subjectReader;
        $this->_paymentTokenInterface = $paymentToken;
        $this->_paymentTokenFactoryInterface = $paymentTokenFactory;
        $this->_paymentExtensionFactory = $paymentExtensionInterfaceFactory;
        $this->_customerSession = $customerSession;
    }

    /**
     * @param array $handlingSubject
     * @param array $response
     * @return array|string[]|void
     * Handles vault response.
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = $this->_subjectReader->readPayment($handlingSubject);
        $payment = $paymentDO->getPayment();
        $adflexData = $this->_checkoutSession->getAdflexData();

        // add vault payment token entity to extension attributes
        $paymentToken = $this->getVaultPaymentToken($adflexData);
        if (!is_null($paymentToken)) {
            $extensionAttributes = $this->getExtensionAttributes($payment);
            $extensionAttributes->setVaultPaymentToken($paymentToken);
        }
    }

    /**
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface|null
     * Gets vault payment token.
     */
    protected function getVaultPaymentToken($adflexData)
    {
        // Check token existing in gateway response, if set to reusable and the customer is logged in,
        // then we know we can save it.
        if ($adflexData['tokenType'] !== 'Reusable'
            || !isset($adflexData['token'])
            || !$this->_customerSession->isLoggedIn()) {
            return null;
        }

        /** @var PaymentTokenInterface $paymentToken */
        // Necessary data for tokenisation.
        $paymentToken = $this->_paymentTokenFactoryInterface->create(PaymentTokenFactoryInterface::TOKEN_TYPE_CREDIT_CARD);
        $paymentToken->setGatewayToken($adflexData['token']);
        $paymentToken->setExpiresAt(strtotime($adflexData['tokenExpiry']));
        try {
            $paymentToken->setTokenDetails($this->_json->serialize([
                'type' => $adflexData['scheme'],
                'maskedCC' => $adflexData['cardLast4Digits'],
                'expirationDate' => $this->getExpirationDate($adflexData),
                'enhancedDataType' => $adflexData['enhancedDataType']
            ]));
        } catch (\Exception $e) {
            $this->_logger->addCritical('Unable to tokenise card within Magento Vault, error:
            ' . $e->getMessage());
        }

        return $paymentToken;
    }

    /**
     * @param $adflexData
     * @return string
     * @throws \Exception
     * Gets card expiration date.
     */
    private function getExpirationDate($adflexData)
    {
        $expDate = new \DateTime(
            $adflexData['cardExpiryYear']
            . '-'
            . $adflexData['cardExpiryMonth']
            . '-'
            . '01'
            . ' '
            . '00:00:00',
            new \DateTimeZone('UTC')
        );
        $expDate->add(new \DateInterval('P1M'));
        return $expDate->format('Y-m-d 00:00:00');
    }

    /**
     * Get payment extension attributes
     * @param InfoInterface $payment
     * @return OrderPaymentExtensionInterface
     */
    private function getExtensionAttributes(InfoInterface $payment)
    {
        $extensionAttributes = $payment->getExtensionAttributes();
        if (null === $extensionAttributes) {
            $extensionAttributes = $this->_paymentExtensionFactory->create();
            $payment->setExtensionAttributes($extensionAttributes);
        }
        return $extensionAttributes;
    }
}
