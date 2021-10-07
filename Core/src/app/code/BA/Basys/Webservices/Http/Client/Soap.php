<?php
namespace BA\Basys\Webservices\Http\Client;

use BA\Basys\Logger\Logger;
use BA\Basys\Webservices\Http\ClientInterface;
use BA\Basys\Webservices\Http\ConverterInterface;
use BA\Basys\Webservices\Http\TransferInterface;
use Magento\Framework\Webapi\Soap\ClientFactory;
use Psr\Log\LoggerInterface;

class Soap implements ClientInterface
{
    /**
     * @var \Magento\Framework\Webapi\Soap\ClientFactory
     */
    protected $clientFactory;

    /**
     * @var \BA\Basys\Webservices\Http\ConverterInterface
     */
    protected $converter;

    /**
     * @var \BA\Basys\Logger\Logger
     */
    protected $logger;

    public function __construct(
        ClientFactory $clientFactory,
        ConverterInterface $converter,
        Logger $logger
    ) {
        $this->clientFactory = $clientFactory;
        $this->converter = $converter;
        $this->logger = $logger;
    }

    public function execute(TransferInterface $transfer)
    {
        $config = $transfer->getConfig() ?? [];
        $client = $this->clientFactory->create(
            $transfer->getUri(),
            $config,
        );

        $log = [];

        try {
            $response = $client->__soapCall(
                $transfer->getMethod(),
                $transfer->getBody()
            );

            $result = $this->converter->convert($response);
            
            return $result;
        } catch (\Exception $e) {
            $this->logger->critical(
                'Failed Call',
                [
                    'method' => $transfer->getMethod(),
                    'exeception' => $e->getMessage(),
                    'request' => $client->__getLastRequest(),
                    'response' =>  $client->__getLastResponse(),
                ]
            );
            
            throw $e;
        }
    }
}
