<?php
namespace BA\BasysCatalog\Model\Config\Structure\Field;

use BA\BasysCatalog\Api\Data\CatalogInterface;

interface CatalogFieldProviderInterface
{
    /**
     * @param \BA\BasysCatalog\Api\Data\CatalogInterface $catalog
     * @return array
     */
    public function process(CatalogInterface $catalog);
}
