<?xml version="1.0" encoding="UTF-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Communication/etc/communication.xsd">
    <topic name="basys.command.queue.customer" request="BA\Basys\Webservices\Command\AsyncCommandMessageInterface" />
    <topic name="basys.command.queue.customer.retry" request="BA\Basys\Webservices\Command\AsyncCommandMessageInterface" />
</config>