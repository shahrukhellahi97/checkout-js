<?php
namespace BA\BasysCatalog\Import\Product\Save;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResourceConnectionFactory;
use Psr\Log\LoggerInterface;

final class Transaction
{
    private $batchSize = 100;

    private $cursor = 0;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $transaction;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        ResourceConnectionFactory $resourceConnection,
        LoggerInterface $loggerInterface
    ) {
        $this->resourceConnection = $resourceConnection->create();
        $this->logger = $loggerInterface;
    }

    public function setBatchSize($size)
    {
        $this->batchSize = $size;
    }

    public function add(
        TableInterface $table,
        ProductInterface $product
    ) {
        if ($table->isMultipleInserts()) {
            foreach ($table->getRows($product) as $row) {
                $this->getTransaction()->insertOnDuplicate(
                    $table->getTable(),
                    $row
                );

                $this->commit();
            }
        } else {
            $this->getTransaction()->insertOnDuplicate(
                $table->getTable(),
                $table->getRows($product)
            );

            $this->commit();
        }
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface 
     */
    private function getTransaction()
    {
        if ($this->transaction == null) {
            $this->transaction = $this->resourceConnection->getConnection()->beginTransaction();
        }

        return $this->transaction;
    }

    public function commit($force = false)
    {
        if ($force) {
            $this->cursor = 0;

            $this->getTransaction()->commit();
            $this->transaction = null;
            // $this->resourceConnection->closeConnection();

            return true;
        } 

        if ($this->cursor >= $this->batchSize) {
            $this->commit(true);
        } else {
            $this->cursor += 1;
        }
    }
}