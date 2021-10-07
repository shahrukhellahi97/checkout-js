<?php
/**
 * Copyright @ 2020 Adflex Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adflex\Payments\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class SavePaymentInformation
 *
 * @package Adflex\Payments\Observer
 * Saves the card token so we can use it.
 */
class SavePaymentInformation extends AbstractDataAssignObserver
{
    const ADFLEX_TOKEN = 'adflex_token';
    const ADFLEX_ADDITIONAL_INFO = 'adflex_additional_info';
    const ADFLEX_SUB_CODE = 'adflex_sub_code';
    const ADFLEX_STATUS_MESSAGE = 'adflex_status_msg';

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    public function __construct(
        Session $session
    ) {
        $this->_checkoutSession = $session;
    }

    /**
     * @param Observer $observer
     * @return void
     * We need to be able to use this data in order to make a request to Adflex, temporarily store it.
     */
    public function execute(Observer $observer)
    {
        $data = $this->readDataArgument($observer);
        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_array($additionalData) || !isset($additionalData[self::ADFLEX_TOKEN])) {
            if (isset($additionalData['cvc'])) {
                $adFlexData = $this->_checkoutSession->getAdflexData();
                if (is_null($adFlexData)) {
                    $this->_checkoutSession->setAdflexData(['cvc' => $additionalData['cvc']]);
                    return;
                } else {
                    $adFlexData['cvc'] = $additionalData['cvc'];
                    $this->_checkoutSession->setAdflexData($adFlexData);
                    return;
                }
            } else {
                return;
            }
        }

        // Get original adflex data used for session creation.
        $origAdflexData = $this->_checkoutSession->getAdflexData();
        // Add to session so we can use later in authorisation. Overwrite what we had previously.
        $adFlexData = [
           'token' => $additionalData[self::ADFLEX_TOKEN],
           'additional_info' => $additionalData[self::ADFLEX_ADDITIONAL_INFO],
           'sub_code' => $additionalData[self::ADFLEX_SUB_CODE],
           'status_message' => $additionalData[self::ADFLEX_STATUS_MESSAGE],
           'transaction_guid' => $origAdflexData['transaction_guid']
       ];

        $this->_checkoutSession->setAdflexData(array_merge($adFlexData, $origAdflexData));
    }
}
