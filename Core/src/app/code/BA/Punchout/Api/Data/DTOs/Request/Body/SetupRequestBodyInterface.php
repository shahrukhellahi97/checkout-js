<?php
namespace BA\Punchout\Api\Data\DTOs\Request\Body;

use BA\Punchout\Api\Data\DTOs\Types\AddressInterface;
use BA\Punchout\Api\Data\DTOs\Types\ContactInterface;
use BA\Punchout\Api\Data\DTOs\Types\UrlInterface;

interface SetupRequestBodyInterface
{
    const BUYER_COOKIE = 'buyer_cookie';

    const BROWSER_FROM_POST = 'browser_from_post';

    const RETURN_URL = 'return_url';

    const CONTACT = 'contact';

    const SHIP_TO ='ship_to';
    
    /**
     * @return string 
     */
    public function getBuyerCookie();

    /**
     * @param string $cookie 
     * @return self 
     */
    public function setBuyerCookie($cookie);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\UrlInterface 
     */
    public function getBrowserFromPost();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\UrlInterface $url 
     * @return self 
     */
    public function setBrowserFromPost($url);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\UrlInterface 
     */
    public function getReturnUrl();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\UrlInterface $url 
     * @return self 
     */
    public function setReturnUrl($url);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\ContactInterface
     */
    public function getContact();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\ContactInterface $contact 
     * @return self 
     */
    public function setContact($contact);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\AddressInterface 
     */
    public function getShipTo();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\AddressInterface $address 
     * @return self
     */
    public function setShipTo($address);
}