<?php
namespace BA\Vertex\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;

class CalculateTaxV implements RequestBuilderInterface
{
    public function build(array $arguments)
    {
        $taxXML = new \SoapVar('<ns1:taxXml>' . $arguments[0] . '</ns1:taxXml>', XSD_ANYXML, 'taxXml');

        return [
            'CalculateTaxV' => [
                'taxXml' => $taxXML,
            ]
        ];
    }
}
