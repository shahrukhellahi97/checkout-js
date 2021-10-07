<?php
namespace BA\BasysCatalog\Import\Product\Save\Post\Link;

use BA\BasysCatalog\Import\Product\Save\Post\PostProcessorInterface;
use Magento\Framework\App\ResourceConnectionFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory;
use Magento\InventoryApi\Api\SourceItemsSaveInterface;

class Levels implements PostProcessorInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory
     */
    protected $sourceItemFactory;

    /**
     * @var \Magento\InventoryApi\Api\SourceItemsSaveInterface
     */
    protected $sourceItemsSave;

    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        SourceItemsSaveInterface $sourceItemsSave,
        SourceItemInterfaceFactory $sourceItemFactory
    ) {
        $this->sourceItemsSave = $sourceItemsSave;
        $this->sourceItemFactory = $sourceItemFactory;
        $this->resourceConnection = $resourceConnectionFactory->create();    
    }

    public function process(AdapterInterface $adapter, array $products)
    {
        $productSkus = array_map(function ($product) {
            return $product->getSku();
        }, $products);

        $newConnection = $this->resourceConnection->getConnection();
        $select = $newConnection->select()
            ->from(
                $newConnection->getTableName('inventory_source_item'),
                ['sku']
            )
            ->where(
                'sku IN (?)',
                $productSkus
            );

        $all = $newConnection->fetchCol($select);

        $addValues = array_filter($products, function ($p) use ($all) {
            return !in_array($p->getSku(), $all);
        });

        $items = [];

        foreach ($addValues as $product) {
            $item = $this->sourceItemFactory->create();

            $item->setSku($product->getSku());
            $item->setSourceCode('default');
            $item->setQuantity(0);
            $item->setStatus(\Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_OUT_OF_STOCK);

            $items[] = $item;

            $adapter->insert(
                $adapter->getTableName('cataloginventory_stock_item'),
                [
                    'product_id' => $product->getId(),
                    'stock_id' => 1,
                    'qty' => 0,
                    'manage_stock' => 1,
                    'stock_status_changed_auto' => 1,
                    'is_in_stock' => 1,
                    'qty_increments' => 1,
                    'max_sale_qty' => 999999
                ]
            );
        }

        if (count($items) >= 1) {
            $this->sourceItemsSave->execute($items);
        }
    }   
}