<?php
namespace BA\BasysCatalog\Import;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\Data\BasysProductInterfaceFactory;
use BA\BasysCatalog\Api\Data\BasysProductPriceInterfaceFactory;
use BA\BasysCatalog\Api\Data\BasysProductPriceTypeInterface;
use BA\BasysCatalog\Import\Queue\QueueInterface;
use BA\BasysCatalog\Import\Queue\QueueProcessorInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\ArrayUtils;

class CatalogImport implements CatalogImportInterface
{
    /**
     * @var \BA\BasysCatalog\Import\ProductQueueProcessorInterface
     */
    protected $productQueueProcessor;

    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    public function __construct(
        ProductQueueProcessorInterface $productQueueProcessor,
        CommandPoolInterface $commandPool
    ) {
        $this->productQueueProcessor = $productQueueProcessor;
        $this->commandPool = $commandPool;
    }

    public function import(int $divisionId, int $catalogId)
    {
        $products = $this->getProductsFromWebservice($divisionId, $catalogId);

        $this->productQueueProcessor->process($catalogId, $products);
    }

    /**
     * @param int $divisionId
     * @param int $catalogId
     * @return \BA\BasysCatalog\Api\Data\BasysProductInterface[]|array
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    private function getProductsFromWebservice(int $divisionId, int $catalogId)
    {

        $command = $this->commandPool->get('product_get_details');

        return $command->execute([
            'division_id' => $divisionId,
            'catalog_id' => $catalogId
        ], [
            'division_id' => $divisionId
        ]);
    }
}