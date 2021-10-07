<?php
namespace BA\BasysCatalog\Import;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\Data\BasysProductPriceInterface;
use BA\BasysCatalog\Api\Data\CatalogInterface;
use BA\BasysCatalog\Import\Queue\QueueInterface;
use Magento\Framework\App\ResourceConnection;

class ProductQueueProcessor implements ProductQueueProcessorInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \BA\BasysCatalog\Import\VersionValidationInterface
     */
    protected $versionValidator;

    /**
     * @var \BA\BasysCatalog\Import\Queue\QueueInterface
     */
    protected $queue;

    public function __construct(
        QueueInterface $queue,
        VersionValidationInterface $versionValidator,
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->versionValidator = $versionValidator;
        $this->queue = $queue;
    }

    public function process(int $catalogId, array $products)
    {
        $basysIds = [];

        /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $product */
        foreach ($products as $product) {
            if (!$this->versionValidator->valid($product)) {
                $this->queue->add($product);
            }

            $basysIds[] = $product->getBasysId();
        }

        $this->clean($catalogId, $basysIds);
    }

    private function clean(int $catalogId, array $ids)
    {
        $connection = $this->resourceConnection->getConnection();

        $connection->update(
            'ba_basys_store_catalog',
            [
                'updated_at' => new \Zend_Db_Expr('NOW()')
            ],
            'catalog_id = ' . (int) $catalogId
        );

        $select = $connection->select()
            ->from(
                $connection->getTableName(BasysProductInterface::SCHEMA)
            )
            ->where(
                'catalog_id = ?',
                $catalogId
            )
            ->where(
                'basys_id NOT IN (?)',
                $ids
            );

        $connection->query($connection->deleteFromSelect(
            $select,
            $connection->getTableName(BasysProductInterface::SCHEMA)
        ));
    }
    
}