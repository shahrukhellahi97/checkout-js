<?php
namespace BA\BasysGiftCertificate\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;
use Psr\Log\LoggerInterface;

class CheckBalance implements RequestBuilderInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function build(array $arguments)
    {
        try {
            $checkBalanceXML = new \SoapVar('<ns1:checkBalance>'. $this->getXml($arguments['CheckBalance']) . '</ns1:checkBalance>', XSD_ANYXML, 'checkBalance');
            
            return [
                'CheckBalance' => [
                    'checkBalance' => $checkBalanceXML
                ],
            ];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
    private function getXml(array $arguments)
    {

        $xml = new \SimpleXMLElement('<CheckBalance />');
        foreach ($arguments as $key => $value) {
            $xml->addChild($key, $value);
        }
        $x = explode("\n", $xml->asXML(), 2)[1];
        return $x;
    }
}
