<?php
namespace BA\BasysOrders\Gateway\Request;

use BA\BasysOrders\Observer\SaveUserDefinedFields;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Customer\Model\Session as CustomerSession;

class UserDefinedFieldsDataBuilder implements BuilderInterface
{
    public function build(array $buildSubject)
    {
        $paymentDO = $buildSubject['payment']->getPayment();
        $result = [];

        $additional = $paymentDO->getAdditionalInformation(
            SaveUserDefinedFields::PAYMENT_UDF
        );

        return $result;
    }
}