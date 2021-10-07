<?php
namespace BA\BasysOrders\Api;

use Magento\Sales\Api\Data\OrderInterface;

interface OrderManagementInterface
{
    public function create(OrderInterface $order);

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order 
     * @param string $xml 
     * @return mixed 
     */
    public function place(array $buildSubject);
}
