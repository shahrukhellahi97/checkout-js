<?php
namespace BA\BasysCustomer\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;
use Psr\Log\LoggerInterface;

class CreateContact implements RequestBuilderInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function build(array $arguments)
    {
        $contactXML = new \SoapVar('<ns1:CustomerContactXML>'. $this->getXml($arguments['Contact']) . '</ns1:CustomerContactXML>', XSD_ANYXML, 'CustomerContactXML');

        return [
            'CreateContact' => [
                'CustomerContactXML' => $contactXML,
                'DefaultCustomerID'  => $arguments['DefaultCustomerID'],
            ],
        ];
    }

    private function getXml(array $arguments)
    {
        $xml = new \SimpleXMLElement('<CustomerContact />');
       
        foreach ($arguments as $key => $value) {
            if (is_array($value)) {
                $child = $xml->addChild($key, $value['__value'] ?? null);
                
                if (is_array($value['__attrs'])) {
                    foreach ($value['__attrs'] as $attrName => $attrValue) {
                        $child->addAttribute($attrName, $attrValue);
                    }
                }

            } else {
                $xml->addChild($key, $value);
            }
        }

        return explode("\n", $xml->asXML(), 2)[1];
    }
}