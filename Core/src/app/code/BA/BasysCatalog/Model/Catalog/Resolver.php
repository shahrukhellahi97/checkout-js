<?php
namespace BA\BasysCatalog\Model\Catalog;

use BA\BasysCatalog\Api\CatalogResolverInterface;
use BA\BasysCatalog\Api\Data\BasysProductPriceInterface;
use BA\BasysCatalog\Helper\Data;
use BA\BasysCatalog\Model\Catalog\Filter\FilterInterface;
use Codeception\Lib\Interfaces\Web;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Resolver implements CatalogResolverInterface
{
    /**
     * @var \BA\BasysCatalog\Api\BasysStoreManagementInterface
     */
    protected $BasysStoreManagement;

    /**
     * @var \BA\BasysCatalog\Model\Catalog\Filter\FilterInterface
     */
    protected $filter;
    
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \BA\BasysCatalog\Api\Data\CatalogInterface $resolvedCatalog 
     */
    protected $resolvedCatalog;

    /**
     * @var \BA\BasysCatalog\Helper\Data
     */
    protected $catalogHelper;

    public function __construct(
        FilterInterface $filter,
        LoggerInterface $logger,
        ResourceConnection $resourceConnection,
        Data $catalogHelper
    ) {
        $this->filter = $filter;
        $this->logger = $logger;
        $this->catalogHelper = $catalogHelper;
        $this->resourceConnection = $resourceConnection;
    }

    public function resolve(array $catalogs)
    {
        if (!$this->resolvedCatalog) {
            $catalogs = array_filter($catalogs, function ($catalog) {
                return $this->filter->test($catalog);
            });

            if (count($catalogs) > 1) {
                $catalogs = $this->reduce($catalogs);

                if (count($catalogs) > 1) {
                    throw new LocalizedException(__(sprintf("Unable to determine catalog, query returned %s results", count($catalogs))));
                }
            }

            $this->resolvedCatalog = array_shift($catalogs);
        }

        return $this->resolvedCatalog;
    }

    private function reduce(array $catalogs)
    {
        $groups = $this->groupByCurrency($catalogs);
        $result = [];

        /** @var \BA\BasysCatalog\Api\Data\CatalogInterface[] $catalogs */
        foreach ($groups as $currency => $catalogs) {
            $highestValueCatalogId = $this->getReducedCatalogIds($catalogs);

            $filtered = array_filter($groups[$currency], function ($catalog) use ($highestValueCatalogId) {
                return $catalog->getId() == $highestValueCatalogId;
            });

            $result[] = array_shift($filtered);
        }

        return $result;
    }

    private function getReducedCatalogIds(array $catalogs)
    {
        $connection = $this->resourceConnection->getConnection();

        if ($this->catalogHelper->getCatalogSelectionMethod() == 2) {
            $catalogs = array_filter($catalogs, function ($catalog) {
                return preg_match($this->catalogHelper->getCatalogSelectionRegex(), $catalog->getName());
            });

            $catalogIds = array_values(array_map(function ($cat) {
                return $cat->getId();
            }, $catalogs));

            return $catalogIds[0];
        } else {
            $sort = $this->catalogHelper->getCatalogSelectionMethod() == 0 ? 'DESC' : 'ASC';

            $catalogIds = array_map(function ($catalog) {
                /** @var \BA\BasysCatalog\Api\Data\CatalogInterface $catalog */
                return $catalog->getId();
            }, $catalogs);

            $special = $connection->select()
            ->from(
                ['g' => BasysProductPriceInterface::SCHEMA],
                [
                    new \Zend_Db_Expr('COUNT(*) as `total`'),
                    new \Zend_Db_Expr('MAX(g.price)'),
                    'g.basys_id',
                ]
            )
            ->where(
                'g.catalog_id IN (?)',
                $catalogIds
            )
            ->group([
                'g.basys_id'
            ])
            ->having(
                '`total` >= ?',
                count($catalogIds)
            );

            $select = $connection->select()
            ->from(
                ['p' => BasysProductPriceInterface::SCHEMA],
                [
                    'p.catalog_id',
                    new \Zend_Db_Expr('SUM(p.price) as `max_price`')
                ]
            )
            ->joinInner(
                [
                    'grp' => $special
                ],
                'grp.basys_id = p.basys_id',
                [],
            )
            ->where(
                'p.catalog_id IN (?)',
                $catalogIds,
            )
            ->group(
                ['p.catalog_id']
            )
            ->order(
                'max_price ' . $sort
            )
            ->limit(1);

            $query = $select->__toString();

            if ($result = $connection->fetchOne($select)) {
                return $result;
            }

            return null;
        }
    }

    private function groupByCurrency(array $catalogs)
    {
        $groups = [];

        /** @var \BA\BasysCatalog\Api\Data\CatalogInterface $catalog */
        foreach ($catalogs as $catalog) {
            $groups[$catalog->getCurrency()][] = $catalog;
        }

        return $groups;
    }
}
