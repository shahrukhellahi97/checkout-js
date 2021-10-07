<?php
namespace BA\BasysOrders\Webservices\Response;

use BA\Basys\Webservices\Response\HandlerInterface;

class ExportOrdersHandler implements HandlerInterface
{
    /**
     * @var \Magento\Framework\Xml\Parser
     */
    protected $parser;

    public function __construct(\Magento\Framework\Xml\Parser $parser)
    {
        $this->parser = $parser;
    }

    public function handle($response, array $additional = [])
    {
        $basysOrderId = $response['CreateOrderResult'];
        
        return $basysOrderId;
    }
}
