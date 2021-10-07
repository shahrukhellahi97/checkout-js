<?php
namespace BA\BasysCatalog\Api;

interface CatalogResolverInterface
{
    /**
     * Return the current active catalog
     *
     * @param  \BA\BasysCatalog\Api\Data\CatalogInterface[]|array
     * @return \BA\BasysCatalog\Api\Data\CatalogInterface|null
     */
    public function resolve(array $catalogs);
}
