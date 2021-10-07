<?php
namespace BA\BasysCustomer\Model;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use BA\BasysCustomer\Api\CustomerManagementInterface;
use BA\BasysCustomer\Api\Data\CustomerContactInterface;
use BA\BasysCustomer\Model\CustomerContactFactory;
use BA\BasysCustomer\Model\ResourceModel\CustomerContact as CustomerContactResource;
use BA\BasysCustomer\Model\Request\Builder\ContactRequest;
use Magento\Customer\Api\Data\CustomerInterface;

class CustomerManagement implements CustomerManagementInterface
{
    /**
     * @var \BA\BasysCustomer\Model\ResourceModel\CustomerContact
     */
    protected $customerContactResource;

    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    /**
     * @var \BA\BasysCustomer\Model\CustomerContactFactory
     */
    protected $customerContactFactory;

    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    /**
     * @var \BA\BasysCustomer\Model\Request\Builder\ContactRequest
     */
    protected $customerRequest;

    public function __construct(
        CustomerContactResource $customerContactResource,
        CustomerContactFactory $customerContactFactory,
        BasysStoreManagementInterface $basysStoreManagement,
        CommandPoolInterface $commandPool,
        ContactRequest $customerRequest
    ) {
        $this->customerContactResource = $customerContactResource;
        $this->customerContactFactory = $customerContactFactory;
        $this->basysStoreManagement = $basysStoreManagement;
        $this->commandPool = $commandPool;
        $this->customerRequest = $customerRequest;
    }

    public function createAsync(CustomerInterface $customer, $divisionId = null, $currency = null)
    {
        if ($this->getContactIdFromEmail($customer->getEmail())) {
            return true;
        }

        $command = $this->commandPool->get('create_contact_async');
        $request = $this->customerRequest->build($customer);

        try {
            $command->execute($request, $this->getAdditional($customer->getEmail(), $divisionId, $currency));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function create(CustomerInterface $customer, $divisionId = null, $currency = null)
    {
        if ($contactId = $this->getContactIdFromEmail($customer->getEmail())) {
            /** @var \BA\BasysCustomer\Model\CustomerContact $model */
            $model = $this->customerContactFactory->create();
            $this->customerContactResource->load($model, $contactId);

            return $model;
        }

        $command = $this->commandPool->get('create_contact');
        $request = $this->customerRequest->build($customer);

        try {
            /** @var \BA\BasysCustomer\Model\CustomerContact $result */
            $result = $command->execute($request, $this->getAdditional($customer->getEmail(), $divisionId, $currency));
            
            $this->customerContactResource->save($result);

            return $result;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getAdditional($emailAddress, $divisionId, $currency)
    {
        return [
            CustomerContactInterface::EMAIL => $emailAddress,
            CustomerContactInterface::USER_TYPE_ID => 1,
            CustomerContactInterface::DIVISION_ID => $divisionId != null ?
                $divisionId :
                $this->basysStoreManagement->getActiveCatalog()->getDivisionId(),
            CustomerContactInterface::CURRENCY => $currency != null ?
                $currency :
                $this->basysStoreManagement->getActiveCatalog()->getCurrency(),
        ];
    }

    public function getContactIdFromEmail(string $email)
    {
        /** @var \BA\BasysCustomer\Model\CustomerContact $model */
        $model = $this->customerContactFactory->create();

        $this->customerContactResource->getCustomerContactForEmail(
            $model,
            $email,
            $this->basysStoreManagement->getActiveCatalog()->getDivisionId(),
            $this->basysStoreManagement->getActiveCatalog()->getCurrency()
        );

        if ($model) {
            return $model->getContactId();
        }

        return null;
    }
}
