<?php
namespace BA\BasysCustomer\Model\Request\Builder\Customer;

use BA\BasysCustomer\Model\Request\Builder\CustomerRequestInterface;
use BA\BasysCatalog\Api\BasysStoreManagementInterface;
use Magento\Customer\Api\Data\CustomerInterface;

class SourceCode implements CustomerRequestInterface
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $basysStoreManagement;

    public function __construct(BasysStoreManagementInterface $basysStoreManagement)
    {
        $this->basysStoreManagement = $basysStoreManagement;
    }

    public function build(CustomerInterface $customer): array
    {
        $sourceCode = $this->basysStoreManagement->getActiveSourceCode();

        return [
            'Contact' => [
                'SourceCode'   => $sourceCode->getName(),
                'SourceCodeID' => $sourceCode->getId()
            ],
        ];
    }
}