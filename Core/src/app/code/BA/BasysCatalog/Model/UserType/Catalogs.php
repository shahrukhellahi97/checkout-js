<?php
namespace BA\BasysCatalog\Model\UserType;

use BA\UserType\Api\UserTypeManagementInterface;

class Catalogs
{
    /**
     * @var \BA\UserType\Api\UserTypeManagementInterface
     */
    protected $userTypeManagement;

    public function __construct(
        UserTypeManagementInterface $userTypeManagement
    ) {
        $this->userTypeManagement = $userTypeManagement;
    }

    public function getActiveCatalogIds(): array
    {
        return [];
    }
}