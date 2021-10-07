<?php
namespace BA\BasysOrders\ViewModel;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\Request\Http;
use Magento\Sales\Api\OrderRepositoryInterface;

class BasysInfo implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $order;

    public function __construct(
        LoggerInterface $logger,
        Http $request,
        OrderRepositoryInterface $order
    ) {
        $this->logger = $logger;
        $this->request = $request;
        $this->order = $order;
    }
    public function getOrderId()
    {
        $order = $this->order->get($this->request->getParam('order_id'));
        return $order->getData('basys_order_id');
    }
}
