<?php
namespace BA\BasysOrders\Gateway\Http\Client;

use BA\Basys\Webservices\Http\ConverterInterface;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class Soap implements ClientInterface
{
    /**
     * @var \Magento\Framework\Webapi\Soap\ClientFactory
     */
    protected $clientFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Payment\Gateway\Http\ConverterInterface
     */
    protected $converter;

    public function __construct(
        \Magento\Framework\Webapi\Soap\ClientFactory $clientFactory,
        \Psr\Log\LoggerInterface $logger,
        ConverterInterface $converter
    ) {
        $this->clientFactory = $clientFactory;
        $this->logger = $logger;
        $this->converter = $converter;
    }

    public function placeRequest(TransferInterface $transfer)
    {
        $client = $this->clientFactory->create(
            $transfer->getUri(),
            $transfer->getClientConfig() ?? []
        );

        try {
            $response = $client->__soapCall(
                $transfer->getMethod(),
                $transfer->getBody()
            );

            $result = $this->converter->convert($response);

            return $result;
        } catch (\Exception $e) {
            $request = [
                'req' => $client->__getLastRequest(),
                'res' => $client->__getLastResponse(),
            ];

            $this->logger->debug('xx', $transfer->getBody());
            $this->logger->error('webservices', $request);

            throw $e;
        }
    }
}
