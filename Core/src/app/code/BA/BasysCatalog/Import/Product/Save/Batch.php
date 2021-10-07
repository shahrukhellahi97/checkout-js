<?php
namespace BA\BasysCatalog\Import\Product\Save;

use BA\BasysCatalog\Import\Product\BatchSaveInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\ObjectManagerInterface;
use Magento\Indexer\Model\IndexerFactory;
use Psr\Log\LoggerInterface;

class Batch implements BatchSaveInterface
{
    const CHUNK_SIZE = 250;
    
    const ACTIONS = [
        \BA\BasysCatalog\Import\Product\Save\Product::class,
        \BA\BasysCatalog\Import\Product\Save\Table\Grouped\ProductLink::class,
        \BA\BasysCatalog\Import\Product\Save\Table\Grouped\Attributes\Order::class,
        \BA\BasysCatalog\Import\Product\Save\Table\Grouped\Attributes\Quantity::class,
    ];

    const ATTRIBUTES = [
        // \BA\BasysCatalog\Import\Product\Save\Attribute\Integer::class,
        \BA\BasysCatalog\Import\Product\Save\Attribute\Decimal::class,
        \BA\BasysCatalog\Import\Product\Save\Attribute\Varchar::class,
        \BA\BasysCatalog\Import\Product\Save\Attribute\Text::class,
    ];

    const POST_PROCESS = [
        \BA\BasysCatalog\Import\Product\Save\Post\Link\Levels::class,
        \BA\BasysCatalog\Import\Product\Save\Post\Link\Website::class
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Catalog\Api\Data\ProductInterface[]
     */
    protected $products;

    /**
     * @var \Magento\Indexer\Model\IndexerFactory
     */
    protected $indexerFactory;

    /**
     * @var \BA\BasysCatalog\Import\Product\Save\Transaction
     */
    protected $transaction;

    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var array
     */
    private $skus = [];

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        ResourceConnection $resourceConnection,
        ObjectManagerInterface $objectManager,
        Transaction $transaction,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        LoggerInterface $logger
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->objectManager = $objectManager;
        $this->transaction = $transaction;
        $this->transactionFactory = $transactionFactory;
        $this->logger = $logger;
    }

    public function add(ProductInterface $product)
    {
        if ($this->isProcessable($product)) {
            $this->products[] = $product;
            $this->skus[$product->getSku()] = true;
        }
    }

    private function process(
        \Magento\Catalog\Api\Data\ProductInterface $product
    ) {
        $processes = [
            self::ACTIONS,
            self::ATTRIBUTES
        ];

        foreach ($processes as $id => $process) {
            foreach ($process as $sub) {
                /** @var \BA\BasysCatalog\Import\Product\Save\TableInterface $object */
                $object = $this->objectManager->create($sub);
                $this->transaction->add($object, $product);

                $this->transaction->commit();
            }

            $this->transaction->commit(true);
        }
    }

    public function isProcessable(ProductInterface $product)
    {
        if (isset($this->skus[$product->getSku()])) {
            return false;
        }

        if ($product->getTypeId() == 'grouped') {
            return count($product->getProductLinks()) >= 1;
        }

        return true;
    }

    public function save()
    {
        $this->transaction->setBatchSize(static::CHUNK_SIZE);

        $this->products = array_filter($this->products, function($product){
            return $product->getSku() != null;
        });

        foreach ($this->products as $product) {
            $this->process($product);
        }

        foreach (static::POST_PROCESS as $class) {
            $connection = $this->resourceConnection->getConnection();
            $transction = $connection->beginTransaction();
            
            /** @var \BA\BasysCatalog\Import\Product\Save\Post\PostProcessorInterface $object */
            $object = $this->objectManager->create($class);
            $object->process($transction, $this->products);

            $transction->commit();
        }

        $connection = $this->resourceConnection->getConnection();
        $transction = $connection->beginTransaction();
        
        foreach ($this->products as $product) {
            /** @var \BA\BasysCatalog\Import\Product\Save\TableInterface $object */
            $object = $this->objectManager->create(\BA\BasysCatalog\Import\Product\Save\Attribute\Integer::class);
            
            $transction->insertOnDuplicate(
                $object->getTable(),
                $object->getRows($product)
            );
        }

        $transction->commit();
    }
}
