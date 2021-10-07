<?php
namespace BA\BasysCustomer\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;
use Psr\Log\LoggerInterface;

class CreateCustomer implements RequestBuilderInterface
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
        $soap = new \SoapVar('<ns1:customerXML>'. $this->getXml($arguments['customer']) . '</ns1:customerXML>', XSD_ANYXML, 'customerXML');

        $x =  [
            'CreateCustomer' => [
                'customerXML' => $soap
            ]
        ];

        return $x;
    }

    private function getXml(array $arguments)
    {

        $xml = new \SimpleXMLElement('<Customer />');
       
        foreach ($arguments as $key => $value) {
            if ($key == 'Country') {
                $child = $xml->addChild($key, $value);
                $child->addAttribute('countryCode', 'GB');
            } elseif ($key == 'BillTo') {
                $billTo = $xml->addChild($key);
                $billChildren = $value;
                foreach ($billChildren as $key => $value) {
                    $billTo->addChild($key, $value);
                }
            } else {
                $xml->addChild($key, $value);
            }
        }
       
        $x = explode("\n", $xml->asXML(), 2)[1];

        return $x;
    }
}