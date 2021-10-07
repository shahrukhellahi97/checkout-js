<?php
namespace BA\Punchout\Api\Data;

use BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface;
use BA\Punchout\Api\Data\CredentialInterface;

interface RequestInterface
{
    const SCHEMA = 'ba_punchout_request';

    const REQUEST_ID = 'request_id';

    const PAYLOAD_ID = 'payload_id';

    const STORE_ID = 'store_id';

    const CUSTOMER_ID = 'customer_id';

    const TOKEN = 'token';

    const TIMESTAMP = 'timestamp';

    const EMAIL = 'email';

    const NAME = 'name';

    const CURRENCY = 'currency';

    const PROCUREMENT_APPLICATION = 'procurement_application';

    const BUYER_COOKIE = 'buyer_cookie';

    const BROWSER_FROM_POST = 'browser_from_post';

    const RETURN_URL = 'return_url';

    const DELIVER_TO = 'deliver_to';

    const STREET = 'street';

    const CITY = 'city';

    const STATE = 'state';

    const POSTCODE = 'postcode';

    const COUNTRY = 'country';

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Request\SetupRequestInterface $request 
     * @return self
     */
    public function createWithSetupRequest(SetupRequestInterface $request);

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\CredentialInterface|BA\Punchout\Api\Data\CredentialInterface $credential 
     * @param int $credentialType 
     * @return self
     */
    public function addCredential(CredentialInterface $credential, int $credentialType);

    /**
     * @return int 
     */
    public function getStoreId();

    /**
     * @param int $token 
     * @return self 
     */
    public function setStoreId($id);

    /**
     * @return int 
     */
    public function getCustomerId();

    /**
     * @param int $id 
     * @return self 
     */
    public function setCustomerId($id);

    /**
     * @return string 
     */
    public function getToken();

    /**
     * @param string $token 
     * @return self 
     */
    public function setToken($token);

    /**
     * @return int
     */
    public function getRequestId();

    /**
     * @param int $id 
     * @return self
     */
    public function setRequestId($id);

    /**
     * @return string|null
     */
    public function getPayloadId();

    /**
     * @param string $payloadId 
     * @return self
     */
    public function setPayloadId($payloadId);

    /**
     * @return string|null
     */
    public function getTimestamp();

    /**
     * @param string $timestamp 
     * @return self
     */
    public function setTimestamp($timestamp);

    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @param string $emailAddress 
     * @return self
     */
    public function setEmail($emailAddress);

    /**
     * @return string|null
     */
    public function getName();
    
    /**
     * @param string $customerName 
     * @return self
     */
    public function setName($customerName);

    /**
     * @return string|null
     */
    public function getCurrency();

    /**
     * @param string $currency 
     * @return self
     */
    public function setCurrency($currency);

    /**
     * @return string|null
     */
    public function getProcurementApplication();

    /**
     * @param string $applicationName 
     * @return self
     */
    public function setProcurementApplication($applicationName);

    /**
     * @return string|null
     */
    public function getBuyerCookie();

    /**
     * @param string $cookie 
     * @return self 
     */
    public function setBuyerCookie($cookie);

    /**
     * @return string|null
     */
    public function getBrowserFromPost();

    /**
     * @param string $url 
     * @return self
     */
    public function setBrowserFromPost($url);

    /**
     * @return string|null
     */
    public function getReturnUrl();

    /**
     * @param string $url 
     * @return string
     */
    public function setReturnUrl($url);

    /**
     * @return string|null
     */
    public function getDeliverTo();

    /**
     * @param string $deliverTo 
     * @return self
     */
    public function setDeliverTo($deliverTo);

    /**
     * @return string|null
     */
    public function getStreet();

    /**
     * @param string $street 
     * @return self
     */
    public function setStreet($street);

    /**
     * @return string|null
     */
    public function getCity();

    /**
     * @param string $city 
     * @return self
     */
    public function setCity($city);

    /**
     * @return string|null
     */
    public function getState();

    /**
     * @param string $state 
     * @return self
     */
    public function setState($state);

    /**
     * @return string|null
     */
    public function getPostalCode();

    /**
     * @param string $postcode 
     * @return string
     */
    public function setPostalCode($postcode);

    /**
     * @return string|null
     */
    public function getCountry();

    /**
     * @param string $country 
     * @return self
     */
    public function setCountry($country);
}