<?php
namespace BA\BasysCatalog\Import;

interface CatalogImportInterface
{
    /**
     * Import BASys catalog from webservice
     *
     * @param int $divisionId
     * @param int $catalogId
     * @return mixed
     */
    public function import(int $divisionId, int $catalogId);
}
