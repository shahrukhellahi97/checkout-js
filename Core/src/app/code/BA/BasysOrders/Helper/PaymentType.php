<?php
namespace BA\BasysOrders\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class PaymentType extends AbstractHelper
{
    const XML_PATH_UDF_AUTOFILL = 'basys_store/p%s/udf%s/autofill';

    const XML_PATH_PAYMENT_TYPE_LABEL = 'basys_store/p%s/label';

    const XML_PATH_UDF_LABEL = 'basys_store/p%s/udf%s/label';

    public function getAutoFillAttributeCode($paymentTypeId, $udfId)
    {
        $path = sprintf(self::XML_PATH_UDF_AUTOFILL, $paymentTypeId, $udfId);

        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function getLabelForPaymentType($paymentTypeId)
    {
        return $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_PAYMENT_TYPE_LABEL, $paymentTypeId),
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    public function getLabelForUDF($paymentTypeId, $udfId)
    {
        return $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_UDF_LABEL, $paymentTypeId, $udfId),
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}