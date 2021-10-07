<?php
namespace BA\BasysOrders\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

class SaveUserDefinedFields extends AbstractDataAssignObserver
{
    const PAYMENT_UDF = 'udf';

    protected $additionalInformationList = [
        self::PAYMENT_UDF,
    ];

    /**
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        // $data = $this->readDataArgument($observer);

        // $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        
        // if (!is_array($additionalData)) {
        //     return;
        // }

        // $paymentInfo = $this->readPaymentModelArgument($observer);

        // foreach ($this->additionalInformationList as $additionalInformationKey) {
        //     if (isset($additionalData[$additionalInformationKey])) {
        //         $paymentInfo->setAdditionalInformation(
        //             $additionalInformationKey,
        //             $additionalData[$additionalInformationKey]
        //         );
        //     }
        // }
    }
}
