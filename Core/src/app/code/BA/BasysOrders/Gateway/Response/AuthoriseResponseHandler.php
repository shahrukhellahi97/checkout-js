<?php
namespace BA\BasysOrders\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\OrderRepositoryInterfaceFactory;

class AuthoriseResponseHandler implements HandlerInterface
{
    protected $orderRepositoryFactory;

    public function __construct(OrderRepositoryInterfaceFactory $orderRepositoryFactory)
    {
        $this->orderRepositoryFactory = $orderRepositoryFactory;
    }

    public function handle(array $handlingSubject, array $response)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $handlingSubject['payment']->getPayment()->getOrder();
        
        $order->setData('basys_order_id', $response['CreateOrderResult']);

        $orderRepo = $this->orderRepositoryFactory->create();
        $orderRepo->save($order);
    }
}
