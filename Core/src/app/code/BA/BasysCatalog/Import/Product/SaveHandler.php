<?php
namespace BA\BasysCatalog\Import\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\TransactionFactory;
use Psr\Log\LoggerInterface;
use Spatie\Async\Pool;

class SaveHandler implements SaveHandlerInterface
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResource;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var \Magento\Framework\DB\Transaction
     */
    protected $transaction;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var \Spatie\Async\Pool
     */
    protected $pool;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ResourceConnection $resourceConnection,
        ProductResource $productResource,
        LoggerInterface $logger,
        TransactionFactory $transactionFactory
    ) {
        $this->productRepository = $productRepository;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->productResource = $productResource;
        $this->transactionFactory = $transactionFactory;
    }

    public function save(ProductInterface $product)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        if (!isset($this->cache[$product->getSku()])) {
            try {
                $this->cache[$product->getSku()] = true;
                $this->productRepository->save($product, true);
            } catch (\Exception $e) {
                $this->logger->critical('oh no', [$e->getMessage()]);
            }
        }
    }
}
