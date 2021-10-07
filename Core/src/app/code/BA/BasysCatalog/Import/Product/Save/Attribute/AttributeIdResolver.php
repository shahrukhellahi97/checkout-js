<?php
namespace BA\BasysCatalog\Import\Product\Save\Attribute;

use Magento\Framework\App\ResourceConnection;

class AttributeIdResolver
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    private $cache = [];

    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * 
     * @param array|string $code 
     * @return array|string
     * @throws \DomainException 
     * @throws \Zend_Db_Select_Exception 
     */
    public function getAttributeId($code)
    {
        if (is_array($code)) {
            $allCodesCached = true;
            $result = [];

            foreach ($code as $attr) {
                if (!isset($this->cache[$attr])) {
                    $allCodesCached = false;
                } else {
                    $result[$attr] = $this->cache[$attr];
                }
            }

            if ($allCodesCached) {
                return $result;
            } else {
                $connection = $this->resourceConnection->getConnection();

                $select = $connection->select()
                    ->from(
                        $connection->getTableName('eav_attribute'),
                        ['attribute_id', 'attribute_code']
                    )
                    ->where(
                        'attribute_code IN (?)',
                        $code
                    )
                    ->where(
                        'entity_type_id = 4'
                    );
                
                if ($result = $connection->fetchAll($select)) {
                    $return = [];

                    foreach ($result as $row) {
                        $return[$row['attribute_code']] = $row['attribute_id'];
                    }

                    $this->cache = array_merge($this->cache, $return);

                    return $return;
                }
            }
        } else {
            if (!isset($this->cache[$code])) {
                $connection = $this->resourceConnection->getConnection();

                $select = $connection->select()
                    ->from(
                        $connection->getTableName('eav_attribute'),
                        ['attribute_id']
                    )
                    ->where(
                        'attribute_code = ?',
                        $code
                    );

                if ($result = $connection->fetchOne($select)) {
                    $this->cache[$code] = $result['attribute_id'];
                }
            }

            return $this->cache[$code];
        }
    }
}