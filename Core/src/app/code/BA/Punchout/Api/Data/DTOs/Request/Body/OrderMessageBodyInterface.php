<?php
namespace BA\Punchout\Api\Data\DTOs\Request\Body;

use BA\Punchout\Api\Data\DTOs\Types\ItemInterface;
use BA\Punchout\Api\Data\DTOs\Types\MoneyInterface;
use BA\Punchout\Api\Data\DTOs\Types\ShippingInterface;
use BA\Punchout\Api\Data\DTOs\Types\UrlInterface;

interface OrderMessageBodyInterface
{
    const BUYER_COOKIE = 'buyer_cookie';
    
    const BROWSER_FROM_POST = 'browser_from_post';
    
    const RETURN_URL = 'return_url';
    
    const TOTAL = 'total';
    
    const SHIPPING = 'shipping';
    
    const ITEMS = 'items';
    
    /**
     * @return string 
     */
    public function getBuyerCookie();

    /**
     * @param string $cookie 
     * @return self 
     */
    public function setBuyerCookie(string $cookie);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\UrlInterface 
     */
    public function getBrowserFromPost();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\UrlInterface $url 
     * @return self 
     */
    public function setBrowserFromPost(UrlInterface $url);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\UrlInterface 
     */
    public function getReturnUrl();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\UrlInterface $url 
     * @return self 
     */
    public function setReturnUrl(UrlInterface $url);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\MoneyInterface
     */
    public function getTotal();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\MoneyInterface $money 
     * @return self
     */
    public function setTotal(MoneyInterface $money);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\ShippingInterface|null
     */
    public function getShipping();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\ShippingInterface $shipping 
     * @return self
     */
    public function setShipping(ShippingInterface $shipping);

    /**
     * @return \BA\Punchout\Api\Data\DTOs\Types\ItemInterface[]
     */
    public function getItems();

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\ItemInterface[]|array $items 
     * @return self 
     */
    public function setItems(array $items);

    /**
     * @param \BA\Punchout\Api\Data\DTOs\Types\ItemInterface $item 
     * @return self 
     */
    public function addItem(ItemInterface $item);
}