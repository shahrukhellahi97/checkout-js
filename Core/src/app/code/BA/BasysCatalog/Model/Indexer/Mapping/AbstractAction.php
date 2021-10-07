<?php
namespace BA\BasysCatalog\Model\Indexer\Mapping;

use Magento\Framework\App\ResourceConnection;

abstract class AbstractAction
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    public function execute($ids)
    {
        $connection = $this->resourceConnection->getConnection();
        $transaction = $connection->beginTransaction();

        $query = $transaction->select()
            ->from(
                ['ent' => $connection->getTableName('catalog_product_entity')],
                ['ent.entity_id']
            )
            ->join(
                ['prd' => $connection->getTableName('ba_basys_catalog_product')],
                'prd.sku = ent.sku',
                ['prd.basyis_id', 'prd.division_id']
            );

        if (is_array($ids)) {
            $query = $query->where(
                'ent.entity_id IN (?)',
                $ids
            );
        } else {
            $query = $query->where(
                'ent.entity_id = ?',
                $ids
            );
        }

        $transaction->insertFromSelect(
            $query, 
            $connection->getTableName('ba_basys_catalog_product_map'),
            ['entity_id', 'basys_id', 'division_id'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INSERT_IGNORE
        );

        $transaction->commit();
    }
}