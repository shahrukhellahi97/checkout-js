<?php
namespace BA\Punchout\Model\DTOs\Request\Body;

use BA\Punchout\Api\Data\DTOs\Request\Body\OrderMessageBodyInterface;
use BA\Punchout\Api\Data\DTOs\Types\ItemInterface;
use BA\Punchout\Api\Data\DTOs\Types\MoneyInterface;
use BA\Punchout\Api\Data\DTOs\Types\ShippingInterface;
use BA\Punchout\Api\Data\DTOs\Types\UrlInterface;
use BA\Punchout\Model\DTOs\Types\AbstractType;
use BA\Punchout\Model\DTOs\Types\Url;

class OrderMessageBody extends AbstractType implements OrderMessageBodyInterface
{
    /**
     * @var \BA\Punchout\Model\DTOs\Types\UrlFactory
     */
    protected $urlFactory;

    /**
     * @var \BA\Punchout\Model\DTOs\Types\MoneyFactory
     */
    protected $moneyFactory;

    /**
     * @var \BA\Punchout\Model\DTOs\Types\ShippingFactory
     */
    protected $shippingFactory;

    public function __construct(
        \BA\Punchout\Model\DTOs\Types\UrlFactory $urlFactory,
        \BA\Punchout\Model\DTOs\Types\MoneyFactory $moneyFactory,
        \BA\Punchout\Model\DTOs\Types\ShippingFactory $shippingFactory,
        array $data = []
    ) {
        $this->urlFactory = $urlFactory;
        $this->moneyFactory = $moneyFactory;
        $this->shippingFactory = $shippingFactory;

        parent::__construct($data);
    }

    public function getBuyerCookie()
    {
        return $this->getData(OrderMessageBodyInterface::BUYER_COOKIE);
    }

    public function setBuyerCookie(string $cookie)
    {
        return $this->setData(OrderMessageBodyInterface::BUYER_COOKIE, $cookie);
    }

    public function getBrowserFromPost()
    {
        if (!$this->hasData(OrderMessageBodyInterface::BROWSER_FROM_POST)) {
            $this->setBrowserFromPost(new Url());
        }

        return $this->getData(OrderMessageBodyInterface::BROWSER_FROM_POST);
    }

    public function setBrowserFromPost(UrlInterface $url)
    {
        return $this->setData(OrderMessageBodyInterface::BROWSER_FROM_POST, $url);
    }

    public function getReturnUrl()
    {
        if (!$this->hasData(OrderMessageBodyInterface::RETURN_URL)) {
            $this->setReturnUrl(new Url());
        }

        return $this->getData(OrderMessageBodyInterface::RETURN_URL);
    }

    public function setReturnUrl(UrlInterface $url)
    {
        return $this->setData(OrderMessageBodyInterface::RETURN_URL, $url);
    }

    public function getTotal()
    {
        if (!$this->hasData(OrderMessageBodyInterface::TOTAL)) {
            $this->setTotal($this->moneyFactory->create());
        }

        return $this->getData(OrderMessageBodyInterface::TOTAL);
    }

    public function setTotal(MoneyInterface $money)
    {
        return $this->setData(OrderMessageBodyInterface::TOTAL, $money);
    }

    public function getShipping()
    {
        if (!$this->hasData(OrderMessageBodyInterface::SHIPPING)) {
            $this->setShipping($this->shippingFactory->create());
        }

        return $this->getData(OrderMessageBodyInterface::SHIPPING);
    }

    public function setShipping(ShippingInterface $shipping)
    {
        return $this->setData(OrderMessageBodyInterface::SHIPPING, $shipping);
    }

    public function getItems()
    {
        return $this->getData(OrderMessageBodyInterface::ITEMS);
    }

    public function setItems(array $items)
    {
        return $this->setData(OrderMessageBodyInterface::ITEMS, $items);
    }

    public function addItem(ItemInterface $item)
    {
        $tmp = $this->getData(OrderMessageBodyInterface::ITEMS);
        $tmp[] = $item;

        return $this->setItems($tmp);
    }
}