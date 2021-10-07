<?php
namespace BA\BasysCatalog\Import;

use BA\Basys\Webservices\Command\CommandPoolInterface;


class AsyncCatalogImport implements CatalogImportInterface
{
    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    public function __construct(CommandPoolInterface $commandPool)
    {
        $this->commandPool = $commandPool;
    }

    public function import(int $divisionId, int $catalogId)
    {
        $command = $this->commandPool->get('product_get_details_async');

        $command->execute([
            'division_id' => $divisionId,
            'catalog_id' => $catalogId
        ], [
            'division_id' => $divisionId
        ]);
    }
}