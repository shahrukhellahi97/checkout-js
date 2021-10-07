<?php
namespace BA\Punchout\Api\Data;

interface CredentialInterface
{
    const SCHEMA = 'ba_punchout_request_credential';

    const CREDENTIAL_ID = 'credential_id';

    const REQUEST_ID = 'request_id';

    const TYPE_ID = 'type_id';

    const IDENTITY = 'identity';

    const SHARED_SECRET = 'shared_secret';

    const DOMAIN = 'domain';

    const USER_AGENT = 'user_agent';
    
    /**
     * @return int
     */
    public function getCredentialId();

    /**
     * @param int $credentialId 
     * @return self
     */
    public function setCredentialId(int $credentialId);

    /**
     * @return int 
     */
    public function getRequestId();

    /**
     * @param int $requestId 
     * @return self
     */
    public function setRequestId(int $requestId);

    /**
     * @return int 
     */
    public function getTypeId();

    /**
     * @param int $typeId 
     * @return self
     */
    public function setTypeId(int $typeId);

    /**
     * @return string|null
     */
    public function getIdentity();

    /**
     * @param string $identity 
     * @return self
     */
    public function setIdentity(string $identity);

    /**
     * @return string|null
     */
    public function getSharedSecret();

    /**
     * @param string $secret 
     * @return self
     */
    public function setSharedSecret(string $secret);

    /**
     * @return string|null
     */
    public function getDomain();

    /**
     * @param string $domain 
     * @return self
     */
    public function setDomain(string $domain);

    /**
     * @return string|null 
     */
    public function getUserAgent();

    /**
     * @param string $userAgent 
     * @return self
     */
    public function setUserAgent(string $userAgent);

}