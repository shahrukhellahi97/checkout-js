<?php
namespace BA\BasysCatalog\Import\Product\Save\Table\Grouped;

use BA\BasysCatalog\Import\Product\Save\Table\IdResolver;
use BA\BasysCatalog\Import\Product\Save\TableInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Framework\App\ResourceConnection;

class ProductLink implements TableInterface
{
    /**
     * @var \BA\BasysCatalog\Import\Product\Save\Table\IdResolver
     */
    protected $idResolver;

    /**
     * @var \BA\BasysCatalog\Import\Product\Save\Util\Product
     */
    protected $productUtil;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    public function __construct(
        IdResolver $idResolver,
        \BA\BasysCatalog\Import\Product\Save\Util\Product $productUtil,
        ResourceConnection $resourceConnection
    ) {
        $this->productUtil = $productUtil;
        $this->idResolver = $idResolver;
        $this->resourceConnection = $resourceConnection;
    }

    public function isMultipleInserts(): bool
    {
        return true;
    }

    public function getTable()
    {
        return 'catalog_product_link';
    }

    public function getRows(ProductInterface $product): array
    {

        $links = $product->getProductLinks();

        if (count($links) >= 1) {
            $productIds = array_map(function ($link) { 
                return $this->productUtil->getProductId($link->getSku());
            }, $links);

            $connection = $this->resourceConnection->getConnection();
            $select = $connection->select()
                ->from(
                    $connection->getTableName($this->getTable()),
                    ['linked_product_id']
                )
                ->where(
                    'product_id = ?',
                    $product->getId()
                )
                ->where(
                    'linked_product_id IN (?)',
                    $productIds
                );

            $found = $connection->fetchCol($select);

            $links = array_filter($links, function ($link) use ($found) {
                /** @var \Magento\Catalog\Api\Data\ProductLinkInterface $link */
                return !in_array(
                    $this->productUtil->getProductId($link->getSku()),
                    $found
                );
            });

            $product->setProductLinks($links);

            $result = [];

            /** @var \Magento\Catalog\Api\Data\ProductLinkInterface $link */
            foreach ($links as $link) 
            {
                $id = $this->idResolver->getNextId($this->getTable());
                $link->setId($id);

                $result[] = [
                    'link_id' => $link->getId(),
                    'product_id' => $product->getId(),
                    'linked_product_id' => $this->productUtil->getProductId($link->getSku()),
                    'link_type_id' => 3,
                ];
            }

            return $result;
        }

        return [];
    }
}