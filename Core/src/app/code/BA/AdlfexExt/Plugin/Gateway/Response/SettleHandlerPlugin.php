<?php
namespace BA\AdflexExt\Plugin\Gateway\Response;

use BA\BasysOrders\Api\OrderManagementInterface;
use BA\BasysOrders\Gateway\Request\OrderDataBuilderComposite;

class SettleHandlerPlugin
{
    /**
     * @var \BA\BasysOrders\Api\OrderManagementInterface
     */
    protected $orderManagement;

    /**
     * @var \BA\BasysOrders\Gateway\Request\OrderDataBuilderComposite
     */
    protected $orderDataBuilder;

    public function __construct(
        OrderManagementInterface $orderManagement
    ) {
        $this->orderManagement = $orderManagement;
    }

    public function aroundHandle(
        \Adflex\Payments\Gateway\Response\SettleHandler $subject,
        callable $proceed,
        array $handlingSubject, 
        array $response
    ) {
        $this->orderManagement->place($handlingSubject);

        $proceed($handlingSubject, $response);
    }
}