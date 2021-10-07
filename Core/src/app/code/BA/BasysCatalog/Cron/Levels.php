<?php
namespace BA\BasysCatalog\Cron;

use BA\Basys\Webservices\Command\CommandPoolInterface;
use BA\BasysCatalog\Api\Data\LevelInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory;
use Magento\InventoryApi\Api\SourceItemsSaveInterface;
use Psr\Log\LoggerInterface;

class Levels implements JobInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \BA\Basys\Webservices\Command\CommandPoolInterface
     */
    protected $commandPool;

    /**
     * @var \BA\Basys\Webservices\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \Magento\InventoryApi\Api\SourceItemsSaveInterface
     */
    protected $sourceItemsSave;

    /**
     * @var \Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory
     */
    protected $sourceItemFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        ResourceConnection $resourceConnection,
        CommandPoolInterface $commandPool,
        SourceItemsSaveInterface $sourceItemsSave,
        SourceItemInterfaceFactory $sourceItemFactory,
        LoggerInterface $logger
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->commandPool = $commandPool;
        $this->sourceItemsSave = $sourceItemsSave;
        $this->sourceItemFactory = $sourceItemFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $connection = $this->resourceConnection->getConnection();

        $leftSelect = $connection->select([])
            ->from(
                'ba_basys_catalog_product', []
            )
            ->columns([
                'basys_id' => new \Zend_Db_Expr("DISTINCT basys_id"),
                'base_colour',
                'trim_colour'
            ]);

        $select = $connection->select()
            ->from(
                ['map' => 'ba_basys_catalog_product_map']
            )
            ->joinLeft(
                ['lvl' => 'ba_basys_catalog_product_level'],
                'map.basys_id = lvl.basys_id'
            )
            ->joinLeft(
                ['prd' => $leftSelect],
                'prd.basys_id = map.basys_id'
            )
            ->joinInner(
                ['ent' => 'catalog_product_entity'],
                'ent.entity_id = map.entity_id'
            )
            ->joinLeft(
                ['stk' => 'inventory_source_item'],
                'stk.sku = ent.sku AND stk.source_code = \'default\'',
                ['stk.quantity', 'stk.status']
            );

        $updateStatus = [];

        $x = $select->__toString();
        
        /** @var \Magento\InventoryApi\Api\Data\SourceItemInterface[] $items */
        $items = [];

        $results = $connection->fetchAll($select);

        foreach ($results as $row) {
            if ($row['level'] == null) {
                $this->placeRequest([
                    'product_id' => $row['basys_id'],
                    'base_colour' => $row['base_colour'],
                    'trim_colour' => $row['trim_colour']
                ]);
            } elseif ((int)$row['level'] != (int) $row['quantity']) {
                /** @var \Magento\InventoryApi\Api\Data\SourceItemInterface $item */
                $item = $this->sourceItemFactory->create();

                $this->logger->info('Setting Stock', [
                    'sku'   => $row['sku'],
                    'level' => $row['level'],
                ]);

                $item->setSku($row['sku']);
                $item->setSourceCode('default');
                $item->setQuantity($row['level']);

                if ((int) $row['level'] >= 1) {
                    $item->setStatus(\Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_IN_STOCK);
                } else {
                    $item->setStatus(\Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_OUT_OF_STOCK);
                }

                $updateStatus[] = [
                    'entity_id' => $row['entity_id'],
                    'status' => $item->getStatus() === \Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_IN_STOCK ? 1 : 2,
                ];

                $items[] = $item;
            }
        }

        if (count($items) >= 1) {
            $this->logger->info('Saving Stock', array_map(function ($item) {
                /** @var \Magento\InventoryApi\Api\Data\SourceItemInterface $item */
                return [
                    'sku' => $item->getSku(),
                    'src' => $item->getSourceCode(),
                    'qty' => $item->getQuantity(),
                ];
            }, $items));

            $this->sourceItemsSave->execute($items);
        }
    }

    private function placeRequest(array $req)
    {
        if (!$this->command) {
            $this->command = $this->commandPool->get('product_get_level_async');
        }
        
        $this->command->execute($req, [
            LevelInterface::BASYS_ID => $req['product_id']
        ]);
    }
}
