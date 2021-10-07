<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Gateway\Request;

use Adflex\Payments\Model\Level3\Types\AmexCpClid;
use Adflex\Payments\Model\Level3\Types\AmexGeneralDataFormat;
use Adflex\Payments\Model\Level3\Types\Mclid;
use Adflex\Payments\Model\Level3\Types\Vgis;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 *
 * @package Adflex\Payments\Model\Level3
 */
class LevelThreeDataBuilder implements BuilderInterface
{
    /**
     * @var \Adflex\Payments\Model\Level3\Types\AmexCpClid
     */
    protected $_amexCpClid;
    /**
     * @var \Adflex\Payments\Model\Level3\Types\AmexGeneralDataFormat
     */
    protected $_amexGeneralDataFormat;
    /**
     * @var \Adflex\Payments\Model\Level3\Types\Mclid
     */
    protected $_mclid;
    /**
     * @var \Adflex\Payments\Model\Level3\Types\Vgis
     */
    protected $_vgis;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_json;

    /**
     * Data constructor.
     *
     * @param \Adflex\Payments\Model\Level3\Types\AmexCpClid $amexCpClid
     * @param \Adflex\Payments\Model\Level3\Types\AmexGeneralDataFormat $amexGeneralDataFormat
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Adflex\Payments\Model\Level3\Types\Mclid $mclid
     * @param \Adflex\Payments\Model\Level3\Types\Vgis $vgis
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        AmexCpClid $amexCpClid,
        AmexGeneralDataFormat $amexGeneralDataFormat,
        ScopeConfigInterface $scopeConfig,
        Mclid $mclid,
        Vgis $vgis,
        Session $session,
        SerializerInterface $serializer
    ) {
        $this->_amexCpClid = $amexCpClid;
        $this->_amexGeneralDataFormat = $amexGeneralDataFormat;
        $this->_mclid = $mclid;
        $this->_vgis = $vgis;
        $this->_checkoutSession = $session;
        $this->_scopeConfig = $scopeConfig;
        $this->_json = $serializer;
    }

    /**
     * @param array $buildSubject
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * Plugin that returns enhanced data if required via proxied classes.
     */
    public function build(array $buildSubject)
    {
        $result = [];
        $enabled = $this->_scopeConfig->getValue(
            'payment/adflex/level3_enabled',
            ScopeInterface::SCOPE_STORE
        );
        // Enable or disable based on customers requirements.
        if ($enabled) {
            $order = $buildSubject['payment']->getPayment()->getOrder();
            $allowedDataTypes = ['MCLID', 'AmexCPCLID', 'AMEXGENERALDATAFORMAT', 'VGIS'];
            $adflexData = $this->_checkoutSession->getAdflexData();
            // Account for a Magento vault payment.
            if (!isset($adflexData['enhancedDataType'])) {
                $payment = $buildSubject['payment']->getPayment();
                $tokenData = $payment->getExtensionAttributes()->getVaultPaymentToken();
                if (!is_null($tokenData)) {
                    $tokenDetails = $this->_json->unserialize($tokenData->getData('details'));
                    if (isset($tokenDetails['enhancedDataType'])) {
                        $adflexData['enhancedDataType'] = $tokenDetails['enhancedDataType'];
                    }
                } else {
                    // We have nothing, we can't do a level 3 transaction.
                    return $result;
                }
            }
            if (isset($adflexData['enhancedDataType'])
                && in_array($adflexData['enhancedDataType'], $allowedDataTypes)) {
                switch ($adflexData['enhancedDataType']) {
                    case 'MCLID':
                        $result = $this->_mclid->generateSpecificData($order);
                        break;
                    case 'AmexCPCLID':
                        $result = $this->_amexCpClid->generateSpecificData($order);
                        break;
                    case 'AMEXGENERALDATAFORMAT':
                        $result = $this->_amexGeneralDataFormat->generateSpecificData($order);
                        break;
                    case 'VGIS':
                        $result = $this->_vgis->generateSpecificData($order);
                        break;
                }

                $result['transactionDetails']['enhancedDataType'] = $adflexData['enhancedDataType'];
                $result['transactionDetails']['type'] = 'Sale';
            }
        }

        /** @var array $result */
        return $result;
    }
}
