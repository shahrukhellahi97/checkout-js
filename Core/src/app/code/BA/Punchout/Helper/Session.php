<?php
namespace BA\Punchout\Helper;

use BA\Punchout\Api\RequestRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\StoreFactory;

class Session
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \BA\Punchout\Api\RequestRepositoryInterface
     */
    protected $requestRepository;

    /**
     * @var \Magento\Store\Model\StoreFactory
     */
    protected $storeFactory;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    public function __construct(
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        CustomerFactory $customerFactory,
        AddressFactory $addressFactory,
        RequestRepositoryInterface $requestRepository,
        StoreFactory $storeFactory,
        CatalogSession $catalogSession
    ) {
        $this->storeFactory = $storeFactory;
        $this->session = $customerSession;  
        $this->requestRepository = $requestRepository;
        $this->checkoutSession = $checkoutSession;
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    /**
     * @return \Magento\Customer\Model\Customer 
     * @throws \Magento\Framework\Exception\LocalizedException 
     * @throws \RuntimeException 
     */
    public function getCustomer()
    {
        return $this->session->getCustomer();
    }

    public function loginWithToken(string $token)
    {
        // Ideally we don't want to push this in here.
        /** @var \BA\Punchout\Model\Request $request */
        $request = $this->requestRepository->loadByToken($token);

        if ($request->getEmail() == null) {
            throw new \Magento\Framework\Exception\AuthenticationException(__("Unknown token"));
        }

        // Customers are anonymous
        // Ignore...
        $customer = $this->createCustomer($request);
        // $this->session->setCustomerAsLoggedIn($customer);

        $this->session->setIsPunchout(true);
        $this->session->setPunchoutRequestId($request->getRequestId());
        // $this->checkoutSession->setCustomerData($customer);
    }

    private function createCustomer(\BA\Punchout\Model\Request $request) : \Magento\Customer\Model\Customer
    {

        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeFactory->create();
        $store->load($request->getStoreId());

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->customerFactory->create();

        $customer->setStore($store);
        // Probably need to be a bit smarter about this;
        $customer->loadByEmail($request->getEmail());

        $customer->setFirstname('Punchout');
        $customer->setLastname('User');
        $customer->setEmail($request->getEmail());

        if ($request->getStreet() != null) {;
            /** @var \Magento\Customer\Model\Address $address */
            $address = $this->addressFactory->create();

            $address->setStreet($request->getStreet())
                ->setCountryId($request->getCountry())
                ->setCity($request->getCity())
                ->setRegion($request->getState())
                ->setTelephone('00000')
                ->setPostcode($request->getPostalCode())
                ->setFirstname($request->getDeliverTo())
                ->setLastname(' ')
                ->setSaveInAddressBook(true)
                ->setIsDefaultBilling(true)
                ->setIsDefaultShipping(true);

            foreach ($customer->getAddresses() as $previous) {
                $previous->getResource()->delete($previous);
            }

            $customer->addAddress($address);
        }

        // $customer->getResource()->save($customer);

        return $customer;
    }
    
    /**
     * @return bool|null
     */
    public function isLoggedIn()
    {
        // return $this->session->isLoggedIn();

        return $this->isPunchoutCustomer();
    }

    public function isPunchoutCustomer(): bool
    {
        if ($this->session->getIsPunchout()) {
            return true;
        }

        return false;
    }

    public function getRequestId()
    {
        return $this->session->getPunchoutRequestId();
    }
    
    /**
     * @return array 
     * @throws \LogicException 
     * @throws \Magento\Framework\Exception\LocalizedException 
     */
    public function getOrderMessage()
    {
        $items = [];

        /** @var \Magento\Quote\Api\Data\CartItemInterface $item */
        foreach ($this->getQuote()->getAllVisibleItems() as $item) {
            $items[] = [
                'sku'      => $item->getSku(),
                'price'    => $item->getPrice(),
                'qty'      => $item->getQty(),
                'currency' => $this->getQuote()->getCurrency()->getStoreCurrencyCode(),
            ];
        }

        return $items;
    }
}