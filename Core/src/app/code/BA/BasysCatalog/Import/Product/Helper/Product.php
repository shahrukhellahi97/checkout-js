<?php
namespace BA\BasysCatalog\Import\Product\Helper;

use BA\BasysCatalog\Api\Data\BasysProductInterface;
use Magento\Framework\App\ResourceConnection;

use function Safe\array_flip;

class Product
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    protected $productCache = [];

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function isGroupedProduct($sku)
    {
        if (!isset($this->productCache[$sku])) {
            $connection = $this->resourceConnection->getConnection();
            $getDivision = $connection->select()
                ->from(
                    BasysProductInterface::SCHEMA . '_queue',
                    new \Zend_Db_Expr('DISTINCT division_id')
                )
                ->where(
                    'sku = ?',
                    $sku
                );

            if ($divisionId = $connection->fetchCol($getDivision)) {
                $getSkus = $connection->select()
                ->from(
                    BasysProductInterface::SCHEMA . '_queue',
                    ['sku']
                )
                ->where(
                    'division_id = ?',
                    $divisionId[0]
                )
                ->where(
                    'sku != report_sku'
                );

                $skus = $connection->fetchCol($getSkus);

                $this->productCache = array_merge($this->productCache, array_flip($skus));
            }
        }

        return isset($this->productCache[$sku]);
    }
}