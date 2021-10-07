<?php
namespace BA\Punchout\Model;

use BA\Punchout\Api\Data\CredentialInterface;
use BA\Punchout\Model\ResourceModel\Credential as ResourceModelCredential;
use Magento\Framework\Model\AbstractModel;

class Credential extends AbstractModel implements CredentialInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModelCredential::class);
    }

    /**
     * @return int
     */
    public function getCredentialId()
    {
        return $this->getData(CredentialInterface::CREDENTIAL_ID);   
    }

    /**
     * @param int $credentialId 
     * @return self
     */
    public function setCredentialId(int $credentialId)
    {
        return $this->setData(CredentialInterface::CREDENTIAL_ID, $credentialId);
    }

    /**
     * @return int 
     */
    public function getRequestId()
    {
        return $this->getData(CredentialInterface::REQUEST_ID);
    }

    /**
     * @param int $requestId 
     * @return self
     */
    public function setRequestId(int $requestId)
    {
        return $this->setData(CredentialInterface::REQUEST_ID, $requestId);
    }

    /**
     * @return int 
     */
    public function getTypeId()
    {
        return $this->getData(CredentialInterface::TYPE_ID);
    }

    /**
     * @param int $typeId 
     * @return self
     */
    public function setTypeId(int $typeId)
    {
        return $this->setData(CredentialInterface::TYPE_ID, $typeId);
    }

    /**
     * @return string|null
     */
    public function getIdentity()
    {
        return $this->getData(CredentialInterface::IDENTITY);
    }

    /**
     * @param string $identity 
     * @return self
     */
    public function setIdentity(string $identity)
    {
        return $this->setData(CredentialInterface::IDENTITY, $identity);
    }

    /**
     * @return string|null
     */
    public function getSharedSecret()
    {
        return $this->getData(CredentialInterface::SHARED_SECRET);
    }

    /**
     * @param string $secret 
     * @return self
     */
    public function setSharedSecret(string $secret)
    {
        return $this->setData(CredentialInterface::SHARED_SECRET, $secret);
    }

    /**
     * @return string|null
     */
    public function getDomain()
    {
        return $this->getData(CredentialInterface::DOMAIN);
    }

    /**
     * @param string $domain 
     * @return self
     */
    public function setDomain(string $domain)
    {
        return $this->setData(CredentialInterface::DOMAIN, $domain);
    }

    /**
     * @return string|null 
     */
    public function getUserAgent()
    {
        return $this->getData(CredentialInterface::USER_AGENT);
    }

    /**
     * @param string $userAgent 
     * @return self
     */
    public function setUserAgent(string $userAgent)
    {
        return $this->setData(CredentialInterface::USER_AGENT, $userAgent);
    }
}