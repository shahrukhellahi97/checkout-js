<?php
namespace BA\BasysCatalog\Import\Product\Save\Table;

use Magento\Framework\App\ResourceConnection;

class IdResolver
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    protected $ids = [];

    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    public function getNextId($tableName)
    {
        if (!isset($this->ids[$tableName])) {
            $connection = $this->resourceConnection->getConnection();

            $select = $connection->select()
                ->from(
                    'information_schema.tables',
                    new \Zend_Db_Expr('AUTO_INCREMENT')
                )
                ->where(
                    'table_name = ?',
                    $tableName
                )
                ->where(
                    'table_schema = ?',
                    new \Zend_Db_Expr('DATABASE()')
                );

            $x = $select->__toString();

            $id = $connection->fetchCol($select)[0];

            $this->ids[$tableName] = $id;
        } else {
            $this->ids[$tableName] += 1;
        }

        return $this->ids[$tableName];
    }
}