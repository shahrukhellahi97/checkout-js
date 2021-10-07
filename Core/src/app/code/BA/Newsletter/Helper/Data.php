<?php
namespace BA\Newsletter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const GROUP_ID = 'basys_newsletter/parameters/group_id';
    const GROUP_NAME = 'basys_newsletter/parameters/group_name';
    const TOKEN = 'basys_newsletter/parameters/token';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function getGroupId()
    {
        return $this->scopeConfig->getValue(self::GROUP_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getGroupName()
    {
        return $this->scopeConfig->getValue(self::GROUP_NAME, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getToken()
    {
        return $this->scopeConfig->getValue(self::TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
