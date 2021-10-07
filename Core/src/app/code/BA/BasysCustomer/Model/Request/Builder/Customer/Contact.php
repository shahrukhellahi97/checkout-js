<?php
namespace BA\BasysCustomer\Model\Request\Builder\Customer;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use BA\BasysCustomer\Model\Request\Builder\CustomerRequestInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\ResourceModel\Address;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Psr\Log\LoggerInterface;

class Contact implements CustomerRequestInterface
{
    /**
     * @var \Magento\Directory\Api\CountryInformationAcquirerInterface
     */
    protected $countryInformationAcquirer;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Address
     */
    protected $addressResource;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    public function __construct(
        CountryInformationAcquirerInterface $countryInformationAcquirer,
        BasysStoreManagementInterface $basysStoreManagement,
        AddressFactory $addressFactory,
        Address $addressResource,
        LoggerInterface $logger
    ) {
        $this->countryInformationAcquirer = $countryInformationAcquirer;
        $this->addressResource = $addressResource;
        $this->addressFactory = $addressFactory;
        $this->logger = $logger;
        $this->basysStoreManagement = $basysStoreManagement;
    }

    public function build(CustomerInterface $customer): array
    {
        if (count($customer->getAddresses()) == 0) {
            $address = $this->getAddress($customer);
        } else {
            $address = $customer->getAddresses()[0];
        }

        $streets = array_pad($address->getStreet(), 3, '');
        $country = $this->countryInformationAcquirer->getCountryInfo(
            $address->getCountryId()
        );

        return [
            'Contact' => [
                'FirstName'   => $customer->getFirstname(),
                'LastName'    => $customer->getLastname(),
                'CompanyName' => $address->getCompany(),
                'Email'       => $customer->getEmail(),
                'Address1'    => $streets[0],
                'Address2'    => $streets[1],
                'Address3'    => $streets[2],
                'City'        => $address->getCity(),
                'PostCode'    => $address->getPostcode(),
                'County'      => $address->getRegion()->getRegionCode(),
                'Telephone'   => $address->getTelephone(),
                'Fax'         => $address->getFax(),
                'Country'     => [
                    '__value' => $country->getFullNameEnglish(),
                    '__attrs' => [
                        'countryCode' => $country->getTwoLetterAbbreviation()
                    ]
                ],
            ],
            'DefaultCustomerID' => $this->basysStoreManagement->getDefaultCustomerId()
        ];
    }

    public function getAddress(CustomerInterface $customer)
    {
        /** @var \Magento\Customer\Api\Data\AddressInterface $address */
        $address = $this->addressFactory->create();
        $this->addressResource->load($address, $customer->getDefaultBilling());

        return $address;
    }
}
