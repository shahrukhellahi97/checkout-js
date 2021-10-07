<?xml version="1.0" encoding="UTF-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework-message-queue:etc/consumer.xsd">
    <consumer name="basys.command.process.customer" 
        queue="basys_command_queue_orders" 
        handler="BA\Basys\Webservices\Consumer\AsyncCommandConsumer::process"
        connection="amqp" />
</config>
