<?php
namespace BA\BasysCatalog\Model\Indexer\Availability;

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
        // move to service contracts and other fun stuff later.
        $connection = $this->resourceConnection->getConnection();

        $transaction = $connection->beginTransaction();
        $transaction->delete('ba_basys_catalog_product_index');

        $innerQuery = $connection->select()
            ->from(
                ['l' => $connection->getTableName('catalog_product_super_link')],
            )
            ->joinInner(
                ['m' => 'ba_basys_catalog_product_map'],
                'm.entity_id = l.product_id'
            );

        $union = $connection->select()
            ->from(
                ['p' => 'ba_basys_catalog_product'],
                ['p.catalog_id']
            )
            ->joinInner(
                ['m' => 'ba_basys_catalog_product_map'],
                'm.basys_id=p.basys_id',
                ['m.entity_id'],
            )
            ->joinInner(
                ['e' => $connection->getTableName('catalog_product_entity')],
                'e.entity_id=m.entity_id',
                ['e.sku']
            );

        $query = $connection->select()
            ->from(
                ['p' => $connection->getTableName('ba_basys_catalog_product')],
                [
                    'p.catalog_id',
                ]
            )
            ->joinInner(
                ['q' => $innerQuery],
                'q.basys_id=p.basys_id',
                [
                    'q.parent_id'
                ]
            )
            ->joinInner(
                ['e' => $connection->getTableName('catalog_product_entity')],
                'e.row_id=q.parent_id',
                [
                    'e.sku'
                ]
            );

        $joiner = $connection->select()
            ->union([
                $query,
                $union
            ]);

        $existingKeys = [];

        foreach ($connection->fetchAll($joiner) as $result) {
            $keyMap = $result['parent_id'] . '.' . $result['catalog_id'];

            if (!isset($existingKeys[$keyMap])) {
                $transaction->insertOnDuplicate(
                    $connection->getTableName('ba_basys_catalog_product_index'),
                    [
                        'entity_id' => $result['parent_id'],
                        'catalog_id' => $result['catalog_id'],
                        'sku' => $result['sku']
                    ]
                );

                $existingKeys[$keyMap] = true;
            }
        }

        $transaction->commit();
    }
}