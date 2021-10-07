<?php
namespace BA\Basys\Helper;

use BA\Basys\Model\ModeInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_DEPLOYMENT_MODE = 'basys/webservices/mode';

    const XML_PATH_ENDPOINT_PRODUCTION = 'basys/webservices/endpoint_production';

    const XML_PATH_ENDPOINT_STAGING = 'basys/webservices/endpoint_staging';

    const XML_PATH_AUTH_USERNAME = 'basys/webservices/auth_username';

    const XML_PATH_AUTH_PASSWORD = 'basys/webservices/auth_password';

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        EncryptorInterface $encryptor
    ) {
        $this->encryptor = $encryptor;

        parent::__construct($context);
    }

    /**
     * Get the correct URL for configured deploment
     *
     * @return string
     */
    public function getUrl()
    {
        $configPath = ($this->getDeploymentMode() == ModeInterface::PRODUCTION) ?
            self::XML_PATH_ENDPOINT_PRODUCTION :
            self::XML_PATH_ENDPOINT_STAGING;

        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @todo implement better logic
     * @param mixed $endpoint
     * @return string
     */
    public function getPathToWsdl($endpoint)
    {
        // Nothing special here.
        $url = rtrim($this->getUrl(), '/') . '/' . $endpoint;

        return $url . '?wsdl';
    }

    /**
     * Get the current deploment mode code
     *
     * @return int
     */
    public function getDeploymentMode()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DEPLOYMENT_MODE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    public function getAuthentication()
    {
        return [
            'login' => $this->getAuthUsername(),
            'password' => $this->getAuthPassword()
        ];
    }

    /**
     * @return string
     */
    public function getAuthUsername()
    {
        return $this->getDecryptedValueFromConfig(self::XML_PATH_AUTH_USERNAME);
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->getDecryptedValueFromConfig(self::XML_PATH_AUTH_PASSWORD);
    }

    /**
     * Get decrypted config value
     *
     * @param string $xmlpath
     * @param string $scope
     * @return string
     */
    private function getDecryptedValueFromConfig(
        string $xmlpath,
        string $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    ) {
        return // $this->encryptor->decrypt(
            $this->scopeConfig->getValue($xmlpath, $scope);
        //);
    }
}
