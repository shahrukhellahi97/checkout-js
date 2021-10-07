<?php
namespace BA\BasysOrders\Webservices\Request;

use BA\Basys\Webservices\Request\RequestBuilderInterface;
use Psr\Log\LoggerInterface;

class ExportOrders implements RequestBuilderInterface
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
            $orderXML = new \SoapVar('<ns1:corporateProgramOrderXML>'. $this->getXml($arguments['Order']) . '</ns1:corporateProgramOrderXML>', XSD_ANYXML, 'corporateProgramOrderXML');
            
            return [
                'CreateOrder' => [
                    'corporateProgramOrderXML' => $orderXML
                ],
            ];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
    private function getXml(array $arguments)
    {
        $xml = new \SimpleXMLElement('<Order />');

        foreach ($arguments as $key => $value) {
            if ($key == 'OrderHeader') {
                $orderHeader = $xml->addChild($key);
                $orderHeader = $this->addShipBillAddress($value, $orderHeader);
            } elseif ($key == 'OrderLines') {
                $orderLines = $xml->addChild($key);
                $orderLines = $this->addOrderLines($value, $orderLines);
            } elseif ($key === 'UserDefinedFields') {
                $udfs = $xml->addChild($key);
                
                foreach ($value as $udf) {
                    $sub = $udfs->addChild('UserDefinedField');

                    $sub->addChild('SequenceNo', $udf['SequenceNo']);
                    $sub->addChild('Value', $udf['Value']);
                }

            } else {
                $xml->addChild($key, $value);
            }
        }
        $x = explode("\n", $xml->asXML(), 2)[1];
        return $x;
    }
    /**
     * Add shipping/billing address to xml
     * @param $orderHeaderChildren []
     * @param $orderHeader xml
     * @return
     */
    private function addShipBillAddress($orderHeaderChildren, $orderHeader)
    {
        try {
            foreach ($orderHeaderChildren as $key => $value) {
                if (($key == 'ShipTo') || ($key == 'BillTo')) {
                    $shipXml = $orderHeader->addChild($key);
                    $shipXml = $this->addSubChildren($value, $shipXml);
                } elseif ($key == 'GiftCertificates') {
                    $giftCertificatesXml = $orderHeader->addChild($key);
                    $giftCertificate = $value;
                    foreach ($giftCertificate as $key => $value) {
                        $giftCertificateXml = $giftCertificatesXml->addChild($key);
                        $giftCertificateXml = $this->addSubChildren($value, $giftCertificateXml);
                    }
                } else {
                    $orderHeader->addChild($key, $value);
                }
            }
            return $orderHeader;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
    /**
     * Add products to the order array
     * @param $orderLinesChildren []
     * @param $orderLines xml
     * @return
     */
    private function addOrderLines($orderLinesChildren, $orderLines)
    {
        try {
            $lineNo = 1;
            foreach ($orderLinesChildren as $orderChild) {
                foreach ($orderChild as $key => $value) {
                    $orderLine = $orderLines->addChild($key);
                    $orderLine->addAttribute('lineNumber', $lineNo++);
                    $orderLine = $this->addSubChildren($value, $orderLine);
                }
            }
            return $orderLines;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function addSubChildren($xmlValues, $xml)
    {
        try {
            foreach ($xmlValues as $key => $value) {
                if ($key == 'Country') {
                    $countryCode = $xml->addChild($key, $value);
                    $countryCode->addAttribute('countryCode', 'GB');
                } else {
                    $xml->addChild($key, $value);
                }
            }
            return $xml;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
