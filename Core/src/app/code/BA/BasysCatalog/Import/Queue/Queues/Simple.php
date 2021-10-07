<?php
namespace BA\BasysCatalog\Import\Queue\Queues;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use BA\BasysCatalog\Api\Data\BasysProductPriceInterface;
use BA\BasysCatalog\Import\Queue\QueueInterface;
use BA\BasysCatalog\Import\Queue\QueueListResultInterfaceFactory;
use BA\BasysCatalog\Model\BasysProductFactory;
use BA\BasysCatalog\Model\ResourceModel\BasysProduct;
use BA\BasysCatalog\Model\ResourceModel\BasysProductPrice;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class Simple implements QueueInterface
{
    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\BasysProductPrice
     */
    protected $priceResource;

    /**
     * @var \BA\BasysCatalog\Model\ResourceModel\BasysProduct
     */
    protected $productResource;

    /**
     * @var \BA\BasysCatalog\Api\Data\BasysProductInterface[]
     */
    private $status;

    /**
     * @var \BA\BasysCatalog\Model\BasysProductFactory
     */
    protected $basysProductFactory;

    /**
     * @var \BA\BasysCatalog\Import\Queue\QueueListResultInterfaceFactory
     */
    protected $queueListResultFactory;

    public function __construct(
        BasysProduct $productResource,
        BasysProductPrice $priceResource,
        BasysProductFactory $basysProductFactory,
        QueueListResultInterfaceFactory $queueListResultFactory
    ) {
        $this->productResource = $productResource;
        $this->priceResource = $priceResource;
        $this->basysProductFactory = $basysProductFactory;
        $this->queueListResultFactory = $queueListResultFactory;
    }

    public function add(BasysProductInterface $product)
    {
        $connection = $this->productResource->getConnection();
        $transaction = $connection->beginTransaction();

        // Terrrrrrifying.
        $transaction->insertOnDuplicate(
            $this->productResource->getMainTable() . '_queue',
            array_merge(
                array_combine(
                    BasysProductInterface::ATTRIBUTES,
                    array_map(function ($key) use ($product) {
                        /** @var \BA\BasysCatalog\Model\BasysProduct $product */
                        return $key == 'version ' ? $product->getChecksum() : $product->getData($key);
                    }, BasysProductInterface::ATTRIBUTES),
                ),
                [
                    'processed' => QueueInterface::QUEUED
                ]
            )
        );

        foreach ($product->getPrices() as $price) {
            $attrs = BasysProductPriceInterface::ATTRIBUTES;
            $transaction->insertOnDuplicate(
                $this->priceResource->getMainTable() . '_queue',
                array_combine(
                    BasysProductPriceInterface::ATTRIBUTES,
                    array_map(function ($key) use ($price) {
                        /** @var \BA\BasysCatalog\Model\BasysProductPrice $price */
                        return $price->getData($key);
                    }, BasysProductPriceInterface::ATTRIBUTES)
                )
            );
        }

        $transaction->commit();
    }

    public function remove(BasysProductInterface $product)
    {
        $this->setStatus($product, QueueInterface::PROCESSED);
    }

    public function clean()
    {
        $connection = $this->productResource->getConnection();
        $transaction = $connection->beginTransaction();

        if (isset($this->status) && count($this->status) >= 1) {
            foreach ($this->status as $status => $products) {
                /** @var \BA\BasysCatalog\Api\Data\BasysProductInterface $product */
                foreach ($products as $product) {
                    $transaction->update(
                        $this->productResource->getMainTable() . '_queue',
                        ['processed' => (int) $status ],
                        [
                            'basys_id = ?' => $product->getBasysId(),
                            // 'catalog_id = ?' => $product->getCatalogId(),
                            'division_id = ?' => $product->getDivisionId()
                        ]
                    );
                }
            }
        }

        // Ideally we should something like above but I must be an idiot, because I can't get it to work?
        // So behold this hideousness
        $useColumns = array_filter(BasysProductInterface::ATTRIBUTES, function ($column) {
            return $column !== BasysProductInterface::PRODUCT_ID;
        });

        $columns = array_map(function ($column) {
            return "`{$column}`";
        }, $useColumns);

        $columns = implode(', ', $columns);

        $update = implode(", ", array_map(function ($column) use ($transaction) {
            return "`{$column}` = q.{$column}";
        }, $useColumns));

        // phpcs:disable
        $transaction->query("
            INSERT INTO `{$this->productResource->getMainTable()}` ({$columns})
            SELECT {$columns} FROM `{$this->productResource->getMainTable()}_queue` q
            WHERE q.processed = 1
            ON DUPLICATE KEY UPDATE
                {$update}
        ");

        $transaction->query($x = "
            INSERT INTO `{$this->priceResource->getMainTable()}`
            SELECT
                q.catalog_id, q.basys_id, q.type_id, q.break, q.price 
            FROM `{$this->priceResource->getMainTable()}_queue` q
            INNER JOIN `{$this->productResource->getMainTable()}_queue` p ON 
                p.catalog_id = q.catalog_id AND 
                p.basys_id = q.basys_id
            WHERE 
                p.processed = 1
            ON DUPLICATE KEY UPDATE 
                `catalog_id` = q.catalog_id,
                `type_id` = q.type_id,  
                `break` = q.break,  
                `price` = q.price
        ");

        // $transaction->query("
        //     DELETE a FROM `{$this->priceResource->getMainTable()}` a
        //     LEFT JOIN ba_basys_catalog_product_price_queue b
        //     ON 
        //         a.catalog_id = b.catalog_id AND
        //         a.basys_id = b.basys_id AND
        //         a.type_id = b.type_id
        //     WHERE 
        //         b.type_id IS NULL
        // ");

        $transaction->query("
            DELETE a FROM `{$this->priceResource->getMainTable()}_queue` a
            INNER JOIN `{$this->productResource->getMainTable()}_queue` b ON 
                a.catalog_id = b.catalog_id AND 
                a.basys_id = b.basys_id;
        ");

        // phpcs:enable
        $transaction->delete($this->productResource->getMainTable() . '_queue', 'processed=1');
        $transaction->commit();
    }

    public function setStatus(BasysProductInterface $product, int $status)
    {
        $this->status[$status][] = $product;
    }

    public function list(SearchCriteriaInterface $searchCriteria)
    {
        $connection = $this->productResource->getConnection();
        $select = $connection->select()
            ->from($this->productResource->getMainTable() . '_queue')
            ->where('processed = ?', QueueInterface::QUEUED);

        foreach ($searchCriteria->getFilterGroups() as $group) {
            foreach ($group->getFilters() as $filter) {
                $select = $select->where(
                    sprintf('%s = ?', $filter->getField()),
                    $filter->getValue(),
                );
            }
        }

        $result = [];

        foreach ($connection->fetchAll($select) as $row) {
            /** @var \BA\BasysCatalog\Model\BasysProduct $product */
            $product = $this->basysProductFactory->create(['data' => $row]);
            $product->setData('queued', true);

            $result[] = $product;
        }

        $list = $this->queueListResultFactory->create([
            'data' => ['products' => $result]
        ]);

        return $list;
    }
}
