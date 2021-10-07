<?php
namespace BA\BasysCatalog\Model\Catalog\Filter;

use BA\BasysCatalog\Api\Data\CatalogInterface;

interface FilterInterface
{
    /**
     * Determine whether this catalog should be available for selection
     *
     * @param \BA\BasysCatalog\Api\Data\CatalogInterface $catalog
     * @return bool
     */
    public function test(CatalogInterface $catalog): bool;
}
