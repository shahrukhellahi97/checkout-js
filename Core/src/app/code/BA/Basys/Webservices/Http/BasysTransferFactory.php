<?php
namespace BA\Basys\Webservices\Http;

use BA\Basys\Helper\Data;
use BA\Basys\Model\ModeInterface;

class BasysTransferFactory implements TransferFactoryInterface
{
    /**
     * @var \BA\Basys\Helper\Data
     */
    protected $helper;

    /**
     * @var string
     */
    protected $endpoint;

    public function __construct(
        Data $helper,
        string $endpoint
    ) {
        $this->helper = $helper;
        $this->endpoint = $endpoint;
    }

    public function create(array $arguments): TransferInterface
    {
        $method = array_keys($arguments)[0];
        $transfer = new Transfer();

        $transfer->setBody($arguments)
            ->setMethod($method)
            ->setConfig($this->getConfig())
            ->setUri($this->getUri());

        return $transfer;
    }

    private function getUri()
    {
        return $this->helper->getPathToWsdl($this->endpoint);
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        $conf = [
            'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL,
            'soap_version' => SOAP_1_2,
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];

        if ($this->helper->getDeploymentMode() == ModeInterface::PRODUCTION) {
            $conf = array_merge($conf, $this->helper->getAuthentication());
        }

        return $conf;
    }
}