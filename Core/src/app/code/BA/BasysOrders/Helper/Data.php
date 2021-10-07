<?php
namespace BA\BasysOrders\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const XML_PATH_PAYMENT_TYPES_ACTIVE = 'basys_store/payment_types/active';

    public function getActivePaymentTypeIds($storeCode = null)
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_PAYMENT_TYPES_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE,
            $storeCode
        );
        
        return $value != null ? explode(',', $value) : [];
    }
}