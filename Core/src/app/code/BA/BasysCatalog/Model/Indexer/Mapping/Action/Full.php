<?php
namespace BA\BasysCatalog\Model\Indexer\Mapping\Action;

use BA\BasysCatalog\Model\Indexer\Mapping\AbstractAction;

class Full extends AbstractAction
{

    public function execute($ids)
    {
        $connection = $this->resourceConnection->getConnection();
        $transaction = $connection->beginTransaction();

        $query = $transaction->select()
            ->from(
                ['ent' => $connection->getTableName('catalog_product_entity')],
                ['ent.entity_id']
            )
            ->joinLeft(
                ['map' => 'ba_basys_catalog_product_map'],
                'map.entity_id = ent.entity_id',
                ['']
            )
            ->join(
                ['prd' => $connection->getTableName('ba_basys_catalog_product')],
                'prd.sku = ent.sku',
                ['prd.basys_id', 'prd.division_id']
            )
            ->where(
                'map.basys_id is NULL'
            );

        $grouped = $connection->select()
            ->from(
                $connection->getTableName('catalog_product_link'),
                [
                    'product_id',
                    new \Zend_Db_Expr('MIN(linked_product_id) AS linked_product_id')
                ]
            )
            ->group(
                'product_id'
            );

        $links = $transaction->select()
            ->from(
                ['e' => $connection->getTableName('catalog_product_entity')],
                ['e.entity_id'],
            )
            ->join(
                ['l' => $grouped],
                'l.product_id = e.entity_id',
                [],
            )
            ->join(
                ['m' => $connection->getTableName('ba_basys_catalog_product_map')],
                'm.entity_id = l.linked_product_id',
                ['m.basys_id', 'm.division_id']
            )
            ->where(
                'e.type_id = ?',
                'grouped'
            );

        $union = $connection->select()
            ->union([
                $query,
                $links
            ]);

        $x = $union->__toString();

        foreach ($transaction->fetchAll($union) as $row) {
            $transaction->insertOnDuplicate(
                $transaction->getTableName('ba_basys_catalog_product_map'),
                [
                    'entity_id' => $row['entity_id'],
                    'basys_id' => $row['basys_id'],
                    'division_id' => $row['division_id'],
                ]
            );
        }

        $transaction->commit();   
    }
    
}