<?php
namespace BA\BasysCatalog\Model\Catalog\Filter\Filters;

use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Model\Catalog\Filter\FilterInterface;
use BA\BasysCatalog\Model\UserType\Catalogs;

class AvailableCatalogs implements FilterInterface
{
    /**
     * @var \BA\BasysCatalog\Model\UserType\Catalogs
     */
    protected $catalog;

    public function __construct(Catalogs $catalog)
    {
        $this->catalog = $catalog;
    }

    public function test(CatalogInterface $catalog): bool 
    {
        return in_array($catalog->getId(), $this->catalog->getActiveCatalogIds());
    }
}