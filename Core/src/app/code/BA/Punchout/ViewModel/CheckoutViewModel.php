<?php
namespace BA\Punchout\ViewModel;

use BA\Punchout\Api\Processor\OrderMessageProcesserInterface;
use BA\Punchout\Helper\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CheckoutViewModel implements ArgumentInterface
{
    /**
     * @var \BA\Punchout\Api\Processor\OrderMessageProcesserInterface
     */
    protected $orderMessageProcesser;

    public function __construct(
        OrderMessageProcesserInterface $orderMessageProcesser,
        Session $session
    ) {
        $this->orderMessageProcesser = $orderMessageProcesser;
        $this->orderMessageProcesser->setRequestId($session->getRequestId());
    }

    public function getTitle(): string
    {
        return 'Hello my guy';
    }

    public function getProcurementApp(): string
    {
        return $this->orderMessageProcesser
            ->getSetupRequest()
            ->getProcurementApplication();
    }

    public function getHookUrl(): string
    {
        return $this->orderMessageProcesser
            ->getOrderMessage()
            ->getPayload()
            ->getReturnUrl()
            ->getUrl();
    }

    public function getOrderMessage(): string
    {
        /** @var \BA\Punchout\Model\DTOs\Request\OrderMessage $order */
        $order = $this->orderMessageProcesser->getOrderMessage();

        $x = $order->toArray();

        return json_encode($x, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}