<?xml version="1.0" encoding="UTF-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework-message-queue:etc/topology.xsd">
    <exchange name="basys.command" type="topic" connection="amqp">
        <binding id="BasysCommandQueueOrders" topic="basys.command.queue.orders" destination="basys_command_queue_orders" destinationType="queue" />
    </exchange>
</config>