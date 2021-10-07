<?xml version="1.0" encoding="UTF-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework-message-queue:etc/topology.xsd">
    <exchange name="basys.command" type="topic" connection="amqp">
        <binding id="BasysCommandQueueCustomer" topic="basys.command.queue.customer" destination="basys_command_queue_customer" destinationType="queue" />
    </exchange>

    <!--
    <exchange name="basys.command.retry" type="topic" connection="amqp">
        <binding id="BasysCommandQueueCustomerRetry" topic="basys.command.queue.customer.retry" destination="basys_command_queue_customer_retry" destinationType="queue">
            <arguments>
                <argument name="x-dead-letter-exchange" xsi:type="string">basys.command</argument>
                <argument name="x-dead-letter-routing-key" xsi:type="number">basys.command.queue.customer</argument>
                <argument name="x-message-ttl" xsi:type="number">36000</argument>
            </arguments>
        </binding>
    </exchange>
-->
</config>