<?php
namespace BA\Vertex\Webservices\Response;

use BA\Basys\Webservices\Response\HandlerInterface;

class CalculateTaxVHandler implements HandlerInterface
{
    /**
     * @var \Magento\Framework\Xml\Parser
     */
    protected $parser;

    /**
     * @var \BA\Vertex\Model\RateFactory
     */
    protected $rateFactory;

    public function __construct(
        \Magento\Framework\Xml\Parser $parser,
        \BA\Vertex\Model\RateFactory $rateFactory
    ) {
        $this->rateFactory = $rateFactory;
        $this->parser = $parser;
    }

    public function handle($response, array $additional = [])
    {
        $x = array_keys($response);
        $xml = $response['CalculateTaxVResult']['any'];

        $x = $this->parser->loadXML($xml)->xmlToArray();

        // phpcs:disable
        $sourceCountryId = $x['Tax']['Destinations']['Destination']['FreightSourceCountry']['_attribute']['countryCode'];
        // phpcs:enable

        $rates = [];

        foreach ($x['Tax']['Destinations']['Destination']['Products'] as $product) {
            $rate = $this->rateFactory->create();

            $rate->setBasysId($product['ProductID'])
                ->setRate($product['TotalSalesTaxRate'])
                ->setSourceCountryId($sourceCountryId)
                ->setProductSourceCountryId($product['SourceCountry']['_attribute']['countryCode']);

            $rates[] = $rate;
        }

        return $rates;
    }
}
