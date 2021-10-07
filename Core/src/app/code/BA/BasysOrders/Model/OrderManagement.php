<?php
namespace BA\BasysOrders\Model;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysOrders\Api\OrderManagementInterface;
use BA\BasysOrders\Gateway\Request\OrderDataBuilderComposite;
use BA\BasysOrders\Model\Request\Builder\OrderRequest;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;

class OrderManagement implements OrderManagementInterface
{
    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    /**
     * @var \BA\BasysOrders\Gateway\Request\OrderDataBuilderComposite
     */
    protected $orderDataBuilder;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        CommandPoolInterface $commandPool,
        OrderDataBuilderComposite $orderDataBuilder,
        LoggerInterface $logger
    ) {
        $this->orderDataBuilder = $orderDataBuilder;
        $this->commandPool = $commandPool;
        $this->logger = $logger;
    }

    public function place(array $buildSubject)
    {
        $command = $this->commandPool->get('place_order_async');

        $command->execute(
            $this->orderDataBuilder->build($buildSubject),
            [
                'durable' => true,
                'durable_key' => $this->getDurabilityKey(),
            ]
        );
    }

    private function getDurabilityKey()
    {
        return sha1(
            implode(':', [
                uniqid('uq_', true),
                hrtime(),
            ])
        );
    }

    public function create(OrderInterface $order)
    {
        return null;
        
        // $request = $this->orderRequestBuilder->build($order);
        // $command = $this->commandPool->get('export_orders');
       
        // try {
        //     $basysOrderId = $command->execute($request);
        //     $this->logger->info('Basys Order Id '.$basysOrderId);
        //     $order->setData('basys_order_id', $basysOrderId);
        //     $order->save();
        //     return $basysOrderId;

        // } catch (\Exception $e) {
        //     $this->logger->error($e->getMessage());
        // }
    }
}
