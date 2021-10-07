<?php
namespace BA\BasysOrders\Gateway\Http;

use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use BA\Basys\Helper\Data as BasysHelper;
use BA\BasysOrders\Webservices\Request\ExportOrders;

class TransferFactory implements TransferFactoryInterface
{
    /**
     * @var \Magento\Payment\Gateway\Http\TransferBuilder
     */
    protected $transferBuilder;

    /**
     * @var \BA\Basys\Helper\Data
     */
    protected $helper;

    /**
     * @var \BA\BasysOrders\Webservices\Request\ExportOrders
     */
    protected $exportOrdersBuilder;

    public function __construct(
        TransferBuilder $transferBuilder,
        BasysHelper $helper,
        ExportOrders $exportOrdersBuilder
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->helper = $helper;
        $this->exportOrdersBuilder = $exportOrdersBuilder;
    }
    
    public function create(array $request)
    {
        $request = $this->formatSoapRequest($request);
        return $this->transferBuilder
            ->setBody($request)
            ->setMethod(array_keys($request)[0])
            ->setUri($this->helper->getPathToWsdl('order.asmx'))
            ->setClientConfig([
                'style' => SOAP_DOCUMENT,
                'use' => SOAP_LITERAL,
                'soap_version' => SOAP_1_2,
                'trace' => true,
                'cache_wsdl' => WSDL_CACHE_NONE
            ])
            ->build();
    }

    /**
     * @param array $request
     * @return array
     */
    private function formatSoapRequest(array $request)
    {
        return $this->exportOrdersBuilder->build($request);
    }
}