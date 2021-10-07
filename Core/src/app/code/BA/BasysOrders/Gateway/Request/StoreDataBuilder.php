<?php
namespace BA\BasysOrders\Gateway\Request;

use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use BA\BasysCustomer\Api\CustomerManagementInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Directory\Model\Region;

class StoreDataBuilder implements BuilderInterface
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \BA\BasysCustomer\Api\CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * @var \Magento\Customer\Api\Data\RegionInterfaceFactory
     */
    protected $regionInterfaceFactory;

    /**
     * @var \Magento\Directory\Model\Region
     */
    protected $region;

    public function __construct(
        BasysStoreManagementInterface $basysStoreManagement,
        CustomerSession $customerSession,
        CustomerManagementInterface $customerManagement,
        CustomerInterfaceFactory $customerFactory,
        AddressFactory $addressFactory,
        RegionInterfaceFactory $regionInterfaceFactory,
        Region $region
    ) {
        $this->basysStoreManagement = $basysStoreManagement;
        $this->customerSession = $customerSession;
        $this->customerManagement = $customerManagement;
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
        $this->regionInterfaceFactory = $regionInterfaceFactory;
        $this->region = $region;
    }

    public function build(array $buildSubject)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $buildSubject['payment']->getPayment()->getOrder();

        $contactId = $this->customerManagement->getContactIdFromEmail(
            $order->getCustomerEmail()
        );

        // if ($contactId == null) {
        //     // Create customer synchronously
        //     $customer = $this->customerManagement->create(
        //         $this->getCustomerObject($order)
        //     );

        //     $contactId = $customer->getContactId();
        // 
        $cat = $this->basysStoreManagement->getActiveCatalog();

        $cat->getCurrency();
        $cat->getDivisionId();
        $cat->getName();
        // Temporary
        return [
            'Order' => [
                'OrderHeader' => [
                    'DivisionID' => $this->basysStoreManagement->getActiveCatalog()->getDivisionId(),
                    'KeyGroupID' => $this->basysStoreManagement->getActiveKeyGroup()->getId(),
                    'CustomerContactID' => $contactId,
                    'SourceCodeID' => $this->basysStoreManagement->getActiveSourceCode()->getId(),
                ]
            ]
        ];
    }

    /**
     * Build customer object
     *
     * @todo REFACTOR REFACTOR, THIS IS HIDEOUS
     * @param \Magento\Sales\Model\Order $order
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function getCustomerObject(\Magento\Sales\Model\Order $order)
    {
        /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
        $customer = $this->customerFactory->create();

        /** @var \Magento\Payment\Gateway\Data\AddressAdapterInterface $billingAddress */
        $billingAddress = $order->getBillingAddress() ?? $order->getShippingAddress();

        $region = $this->region->loadByCode(
            $billingAddress->getRegionCode(),
            $billingAddress->getCountryId()
        );

        $regionInterface = $this->regionInterfaceFactory->create()
            ->setRegionCode($region->getCode())
            ->setRegionId($region->getId())
            ->setRegion($region->getName());
        
        /** @var \Magento\Customer\Api\Data\AddressInterface $address */
        $address  = $this->addressFactory->create();
        $address->setCity($billingAddress->getCity())
            ->setCompany($billingAddress->getCompany())
            ->setPostcode($billingAddress->getPostcode())
            ->setRegion($regionInterface)
            ->setCountryId($billingAddress->getCountryId())
            ->setFirstname($billingAddress->getFirstname())
            ->setLastname($billingAddress->getLastname())
            ->setStreet([
                $billingAddress->getStreetLine1(),
                $billingAddress->getStreetLine2()
            ]);

        $customer->setFirstname($order->getCustomerFirstname())
            ->setLastname($order->getCustomerLastname())
            ->setEmail($order->getCustomerEmail())
            ->setAddresses([$address]);

        return $customer;
    }
}
