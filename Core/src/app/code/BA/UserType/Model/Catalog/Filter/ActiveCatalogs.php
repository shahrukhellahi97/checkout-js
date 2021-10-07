<?php
namespace BA\UserType\Model\Catalog\Filter;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\Catalog\Filter\FilterInterface;
use BA\UserType\Api\UserTypeManagementInterface;

class ActiveCatalogs implements FilterInterface
{
    /**
     * @var \BA\UserType\Api\UserTypeManagementInterface
     */
    protected $userTypeManagement;

    public function __construct(UserTypeManagementInterface $userTypeManagement)
    {
        $this->userTypeManagement = $userTypeManagement;
    }

    public function test(CatalogInterface $catalog): bool
    {
        $cats = $this->userTypeManagement->getEnabledCatalogIds();

        if (count($cats)) {
            return in_array($catalog->getId(), $cats);
        }

        return true;
    }
}