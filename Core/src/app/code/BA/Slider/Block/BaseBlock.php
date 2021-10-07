<?php
namespace BA\Slider\Block;

use Magento\Framework\UrlFactory;

class BaseBlock extends \Magento\Framework\View\Element\Template
{
    protected $_devToolHelper;
    protected $_urlApp;
    protected $_config;
    /**
     * @param \BA\Slider\Block\Context $context
     * @param \Magento\Framework\UrlFactory $urlFactory
     */
    public function __construct(\BA\Slider\Block\Context $context)
    {
        $this->_devToolHelper = $context->getRequestHelper();
        $this->_config = $context->getConfig();
        $this->_urlApp=$context->getUrlFactory()->create();
        parent::__construct($context);
    }
    public function getEventDetails()
    {
        return  $this->_devToolHelper->getEventDetails();
    }
    /**
     * Function for getting current url
     * @return string
     */
    public function getCurrentUrl()
    {
         return $this->_urlApp->getCurrentUrl();
    }
    /**
     * Function for getting controller url for given router path
     * @param string $routePath
     * @return string
     */
    public function getControllerUrl($routePath)
    {
        return $this->_urlApp->getUrl($routePath);
    }
    /**
     * Function for getting current url
     * @param string $path
     * @return string
     */
    public function getConfigValue($path)
    {
        return $this->_config->getCurrentStoreConfigValue($path);
    }
    /**
     * Function canShowRequest
     * @return bool
     */
    public function canShowRequest()
    {
        $isEnabled=$this->getConfigValue('request/module/is_enabled');
        if ($isEnabled) {
            $allowedIps=$this->getConfigValue('request/module/allowed_ip');
            if ($allowedIps == 'null') {
                return true;
            } else {
                $remoteIp = $_SERVER['REMOTE_ADDR'];
                if (strpos($allowedIps, $remoteIp) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
    public function displayBottomContent()
    {
        return $this->getConfigValue('trade_account_settings/settings/bottom_content');
    }
}
