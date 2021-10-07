<?php
namespace BA\Vertex\Model\Request\Builder\Tax;

use BA\Vertex\Model\Request\Builder\CalculateTaxRequestInterface;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Quote\Model\Quote;

class AddFreight implements CalculateTaxRequestInterface
{
    /**
     * @var \Magento\Directory\Api\CountryInformationAcquirerInterface
     */
    protected $countryInformation;

    public function __construct(
        CountryInformationAcquirerInterface $countryInformation
    ) {
        $this->countryInformation = $countryInformation;
    }

    public function build(Quote $quote)
    {
        $freight = $quote->getShippingAddress() ?? 0.00;
        $destinationCountry = $freight->getCountryId() ?? 'GB';

        $country = $this->countryInformation->getCountryInfo($destinationCountry);

        return [
            'Destinations' => [
                'Destination' => [
                    'DestinationCountry' => [
                        $destinationCountry,
                        $country->getFullNameEnglish(),
                    ],
                    'FreightSourceCountry' => [
                        'GB',
                        'United Kingdom'
                    ],
                    'Freight' => $freight->getShippingAmount()
                ]
            ]
        ];
    }
}
